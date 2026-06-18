<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Mcq;
use App\Models\Result;
use App\Models\UploadLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser; // Add this import
use stdClass;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        // // 1. Find the record by its primary key
        // $document = Document::find(1);

        // // 2. Change the attributes
        // $document->status = 'active';

        // // 3. Persist changes to the database
        // $document->save();

        // Filter by the status column where the value is 'active'
        $documents = Document::where('status', 'active')
                    ->where('user_id', auth()->id())
                    ->filter(request(['query']))->get();
        return view('document.index', compact('documents'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('document.upload');
    }

    /**
     * Store a newly created resource in storage.
     * this function is for user to upload the file and it will extract it
     * and it will send to chatgpt
     * then it will save to database
     */
    public function store(Request $request)
    {
        // it will see what is in the request and it will see if there is a file or not
        // dd($request);

        $limit = UploadLog::where('user_id', auth()->id())
                ->where('upload_date', Carbon::today()->toDateString())
                ->first();

        // $limit = new stdClass();
        // $limit->total_upload = 5;
            
        
        

        if ($limit && $limit->total_upload >= 5) {
            echo "<script>alert('Upload limit reached for today. Please try again tomorrow.'); window.location.href='" . url()->previous() . "';</script>";
            exit;
        }

        // 1. Validate that a file is uploaded
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10240', // 10MB max 10240
        ]);

        // access the file on the https
        $file = $request-> file('pdf_file');
        // dd($file);

        // 2. it fetch the name
        // for better use hashName();
        // both will fetch the name but one will hash 

        $fileName = $file -> getClientOriginalName(); 

        
        // dd($fileName); 
        // output = 1771924853_LAST_MINUTES_TEST.pdf


        // 3. EXTRACTION
        // we want to extract the file using pdfparser

        try {
            // to use it must initialize it first.

            $parser = new Parser();


            // 3. Retrieve the temporary file path from the request
            $filePath = $request->file('pdf_file')->getPathname();
            // dd($filePath);

            // output = C:\xampp\tmp\phpDFB1.tmp
            

            // Parse the file and extract text
            $pdf = $parser->parseFile($filePath);

            // it will display the text after the extraction
            $text = $pdf->getText();
            // dd($text);


            // convert it (future self , please explain in detail)
            $cleanText = mb_convert_encoding($pdf->getText(), 'UTF-8', 'UTF-8');
                        
            // Store in session for later use or return to view
            session(['extracted_text' => $text]);

            // Return to a results view with the extracted text
            // return view('document.results', [
            //     'extracted_text' => $text,
            //     'status' => 'success'
            // ]);

        } catch (\Exception $e) {

        // if one of it failm error occur

            return back()->with('error', 'Failed to process PDF: ' . $e->getMessage());
        }


        // 4. DATABASE TRANSACTION & API CALL
        $targetFilePath = null;
        $today = Carbon::today()->toDateString();

        // return DB::transaction(function () use ($file, $fileName, $cleanText, $user, $today, $log, &$targetFilePath) {



        try{
            // Save File to Storage (local storage under storage/app/uploads)
            // $targetFilePath = $file->storeAs('uploads', time() . '_' . $fileName);
            $targetFilePath = "uploads/" . time() . "_" . $fileName;

            // OpenAI API Call via Laravel HTTP client
            $prompt = "Generate exactly 10 multiple choice questions based on: \n\n" . mb_substr($cleanText, 0, 3500) . 
                      "\n\nReturn ONLY a JSON array of objects with keys: question, option_a, option_b, option_c, option_d, answer (A, B, C, or D).";

//             Why this is necessary: PDFs are notorious for using strange font encodings. When pdfparser extracts text, it often pulls out hidden "ghost" characters, invalid byte sequences, or malformed UTF-8 characters.

        // If you pass malformed text into a json_encode() function or send it over an HTTP API (like OpenAI), the JSON encoder will completely break and return false or crash.

        // This line forces PHP to look at the text, strip out or repair any corrupted bytes, and ensure it is strictly, cleanly formatted UTF-8. It acts as a safety shield before you send the text to OpenAI

            $response = Http::withToken(config('services.openai.key'))
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => "You are a teacher's assistant. You respond only in valid JSON arrays."],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.3
                ]);

            if ($response->failed()) {
                throw new \Exception("OpenAI Connection Error: " . $response->body());
            }

            $apiResult = $response->json();

            // ADD THIS LINE HERE TO LOG THE ENTIRE RESPONSE
            Log::info('OpenAI Response Data:', ['result' => $apiResult]);

            // dump($apiResult); // For debugging purposes, you can remove this in production

            // file_put_contents(storage_path('app/uploads/api_response.json'), json_encode($apiResult, JSON_PRETTY_PRINT));
            $jsonContent = $apiResult['choices'][0]['message']['content'] ?? null;

            // dump($jsonContent); // For debugging purposes, you can remove this in production
            
            Log::info('OpenAI Response Data:', ['jsonContent' => $jsonContent]);

            if (!$jsonContent) {
                throw new \Exception("AI failed to generate content. Please try again.");
            }

            $content = json_decode($jsonContent, true);
            
            Log::info('OpenAI Response Data:', ['content' => $content]);

            // dump($content); // For debugging purposes, you can remove this in production

            $mcqs = $content['questions'] ?? $content;

            Log::info('OpenAI Response Data:', ['mcqs' => $mcqs]);

            // dump($mcqs); // For debugging purposes, you can remove this in production

            if (!is_array($mcqs) || empty($mcqs)) {
                throw new \Exception("AI returned empty or invalid question set.");
            }

            // Insert Document
            $document = Document::create([
                // 'user_id' => 1,
                'user_id' => auth() -> id(),
                'filename' => $fileName,
                'original_file_path' => $targetFilePath,
            ]);

            // Insert Questions
            foreach ($mcqs as $q) {
                Mcq::create([
                    'doc_id' => $document->id,
                    'question'    => $q['question'],
                    'option_1'    => $q['option_a'],
                    'option_2'    => $q['option_b'],
                    'option_3'    => $q['option_c'],
                    'option_4'    => $q['option_d'],
                    'correct_answer'=> $q['answer'],
                ]);
            }

            // Update or Create Log
            UploadLog::updateOrCreate(
                ['user_id' => auth()->id(), 'upload_date' => $today],
                ['total_upload' => DB::raw('total_upload + 1')]
            );

            return redirect()->route('document.index')->with('success', 'Success! Quiz generated.');

            } catch (\Exception $e) {
                // Delete file manually if transaction fails (Laravel DB transaction won't undo disk storage files)
                // Storage::delete($targetFilePath);
                return back()->with('error', 'Error: ' . $e->getMessage());
            }


        // }, 5); // Retries 5 times if a deadlock occurs

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        // 
        // dd($document);

        $new_input_name = trim($request->new_name);
        // dd($new_input_name);

        // output : what u type

        
        $cleanBaseName = preg_replace("/[^a-zA-Z0-9_\-]/", " ", $new_input_name);
        // dd($cleanBaseName);

        $old_path = $document->original_file_path;
        // dd($old_path);

        // output: 
        // uploads/1780651266_1771924853_LAST_MINUTES_TEST.pdf

        
        // take the folder upload
        $directory = dirname($old_path);
        // dd($directory); //uploads

        $filename_only = basename($old_path);
        // dd($filename_only);// 1780651266_1771924853_LAST_MINUTES_TEST.pdf

        // pisahkan
        $parts = explode("_", $filename_only, 2);
        // dd($parts); 
        //   0 => "1780651266"
        //   1 => "1771924853_LAST_MINUTES_TEST.pdf"

        $timestamp = $parts[0];
        // dd($timestamp); // 1780651266

        
        $ext = pathinfo($document['filename'], PATHINFO_EXTENSION);
        // dd($ext); //pdf

        
        // gabungkan utk simpan dlm database
        $newPhysicalFilename = $timestamp . "_" . $cleanBaseName . "." . $ext;
        // dd($newPhysicalFilename); // 1780651266_test.pdf

        $newDbFilename = $cleanBaseName . "." . $ext; 
        // dd($newDbFilename); // test.pdf

        $new_path = $directory . "/" . $newPhysicalFilename;
        // dd($new_path); // "uploads/1780651266_test.pdf"

        // dd(Storage::disk('local')->path($old_path));
        
        // if (Storage::disk('local')->exists($old_path)) {
            try {
                // Rename the physical file
                // Storage::disk('local')->move($old_path, $new_path);

                // Update database record
                $document->update([
                    'filename' => $newDbFilename, 
                    'original_file_path' => $new_path,
                ]);

                return redirect()->back()->with('success', 'Document has been renamed.');

            } catch (\Exception $e) {
                // Log the error if the moving process itself fails (e.g., permissions)
                Log::error("Failed to rename file: " . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Could not physically rename the file.']);
            }
        // } else{
            
                return redirect()->back()->withErrors(['error' => 'Could not physically find the file.']);   
        // }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
        // 1. Update the status of this specific document to 'inactive'
        $document->update([
            'status' => 'inactive'
        ]);

        // 2. Redirect the user back with a success message
        return redirect()->back()->with('success', 'Document has been deleted.');
    }


    public function mcq(Document $document){
        $doc_id = $document -> id;
        // dd($doc_id); // 3

        $mcqs = Mcq::where('doc_id' , $doc_id)
        -> where ('status', 'active')
        -> get()
        -> toArray();

        return view('document.mcq', compact('mcqs', 'document'));
    }

    public function answer(Request $request ,Document $document){
        // dump($request-> submit);
        $doc_id = $document -> id;
        // dump($doc_id);

        $mcqs = Mcq::where('doc_id' , $doc_id)
        -> where ('status', 'active')
        -> get();
        // dump($mcqs);

        $total_questions = $mcqs -> count();
        // dump($total_question);

        $question = $mcqs -> toArray();
        // dump($question);

        $score = 0;
        $submitted = false;

        // dump($request -> answers); // it can be in object and assoc array



        if(isset($request["submit"])){
            $submitted = true;
            $answers = isset($request["answers"]) ? $request["answers"] : [] ;

            foreach($answers as $key => $value){
                foreach($mcqs as $mcq){
                    if($mcq['id'] == $key && $mcq['correct_answer'] == $value){
                        $score++;
                    }
                }
            }

            
            // dump($doc_id, $score, $total_question);


            Result::create([
                'doc_id' => $doc_id,
                'score' => $score,
                'total_questions' => $total_questions,
            ]);

        }

            return redirect()->back()->with([
            'doc_id' => $doc_id,
            'score' => $score,
            'total_questions' => $total_questions,
            'submitted' => true
        ]);
    }

    public function chooseresult()
    {
        // Filter by the status column where the value is 'active'
        $documents = Document::where('status', 'active')->filter(request(['query']))->get();
        return view('document.viewresults', compact('documents'));
    }

    public function result(Document $document)
    {
        // dd($document);

        $doc_id = $document -> id;
        // dump($doc_id);

        $results = Result::where('doc_id', $doc_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();

        // dump($results);
        
            

        return view('document.checkresult', compact('results'));
    }

}

