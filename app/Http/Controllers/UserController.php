<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use PHPMailer\PHPMailer\PHPMailer;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('user.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('user.register');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dump($request);
        $formFields = $request -> validate([
            'firstname' => ['required', 'min:3'],
            'lastname' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required']
        ]);

        // dd($formFields);

        
        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        User::create($formFields);
        
        return redirect('/login')->with('message', ' Registration successful! Please log in.');
        
    }

    public function forget(){
        return view ('user.forgetpassword');
    }

    public function sendtoken(Request $request){
        // dd($request);
        $email = $request -> email;
        // dd($email);
        // SAVE EMAIL TO SESSION so we don't have to ask for it again
        session(['reset_email' => $email]);

        $user = User::where('email', $email)
        ->where('status', 'active')
        ->first();

        if($user){
            PasswordReset::where('user_id', $user->id) -> delete();

                
            // Generate Token
            $token_raw = bin2hex(random_bytes(3/*32*/)); 
            $token_hash = password_hash($token_raw, PASSWORD_DEFAULT); // Hash it for DB security
            $expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));

            $sentToken = PasswordReset::create([
                    'user_id' => $user->id,
                    'token' => $token_hash,
                    'expires_at' => $expires
                ]);
            
            if($sentToken){
                    // Send Email
                    $mail = new PHPMailer(true);
                    $emailAddress = config('services.email.username');
                    $password = config('services.password.password');

                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = $emailAddress;
                        $mail->Password   = $password;
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;
                        $mail->SMTPDebug = 2; // Alternative: SMTP::DEBUG_SERVER

                        $mail->setFrom($emailAddress, 'System Admin');
                        $mail->addAddress($email);

                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Token';
                        $mail->Body    = "Your token: <b>$token_raw</b>";

                        $mail->SMTPOptions = array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            )
                        );

                        $mail->send();
                        $message = "Token sent! Check your email.";
                    } catch (Exception $e) {
                        $message = "Mailer Error: {$mail->ErrorInfo}";
                    }
                }
        } else {
            $message = "If email exists, token sent.";
        }

        return back() -> with('message', $message)->with('email', $email);
        
    }

    public function resetPassword(Request $request){
        // dd($request->all());
        // 1. SAFELY GET EMAIL
        // First, try to get it from the Session.

        if(session() -> has('email')){
            $email = session('email');
        }

        // If not in session, try getting it from the hidden form input
        elseif (isset(request()->email)) {
            $email = request()->email;
        } 
        // If neither exists, we cannot proceed
        else {
            die("Error: Session expired or email missing. Please reload and try again.");
        }

        $inputToken = $request->token_input;
        $new_password = $request->new_password;

        $user = User::where('email', $email)
        -> where('status', 'active')
        -> first();

        if($user){
            $user_id = $user -> id;

            $reset = PasswordReset::where('user_id', $user_id) -> first();
            
            if($reset){
                // 4. Verify Expiry
                if (strtotime($reset -> expires_at) < time()) {
                    $message = "Token has expired. Please request a new one.";
                } 

                elseif(password_verify($request -> token_input, $reset -> token)){

                    $resetPass = User::where('id', $user_id) ->first();

                    if($resetPass){
                                    
                        $new_password = bcrypt($new_password);
                        $resetPass -> password = $new_password;
                        $resetPass -> save();

                        // Clean up token and session
                        PasswordReset::where('user_id', $user_id)->delete();
                        session()->forget('email');

                        return redirect('/login')->with('message', 'Password updated successfully! Please login.');
                    }

                }

                return back()->with('message', 'Invalid or expired token. Please try again.');
            }
        }

    }

    // Optional helper for the "Start over" link
    public function clearResetSession()
    {
        session()->forget('reset_email');
        return back();
    }

    public function manageProfile(){

        $user_id = auth()->id();
        $user_data = User::where('id', $user_id) -> first();

        return view('user.manageprofile', compact('user_data'));
    }

    public function editProfile(){

        $user_id = auth()->id();
        $user_data = User::where('id', $user_id) -> first();

        return view('user.updateprofile', compact('user_data'));
    }

    public function updateProfile(Request $request){
        // dd($request->all());
        $firstname = $request -> input('first-name');
        $lastname = $request -> input('last-name');
        // dd($firstname, $lastname);

        $user_id = auth()->id();
        $user = User::where('id', $user_id) -> first();

        if($user){
            $user -> firstname = $firstname;
            $user -> lastname = $lastname;
            $user -> save();

            return back() -> with('message', 'Profile updated successfully!');
        }

        else {
            return back() -> with('error_message', 'Error updating profile. Please try again.');
        }
    }


    public function updatePassword(Request $request){

        // dd($request->all());
        $current_password = $request -> input('current_password');
        $new_password = $request -> input('new_password');
        $confirm_password = $request -> input('confirm_password');

        // dd($current_password, $new_password, $confirm_password);

        $sql_pwd = User::where('id', auth()->id()) -> first();

        if($sql_pwd){
            $sql_pwd = $sql_pwd -> password;
            // dd($sql_pwd);
            if(Hash::check($current_password, $sql_pwd)){
                if($new_password === $confirm_password){
                    $user = User::where('id', auth()->id()) -> first();
                    if($user){
                        $user -> password = bcrypt($new_password);
                        $user -> save();
                        return back() -> with ('password_message', "Password updated successfully!");
                    }
                    else{
                        return back() -> with('password_error', 'Error updating password.');
                    }
                }
                
                else{
                    return back() -> with ('password_error', 'Current password is incorrect.');
                }
            }

        }
         else {
            return back() -> with('password_error', 'Error updating password.');
        }

    }



    // Logout User
    public function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', "You have been logged out!");

    }

    
    // Authenticate User
    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect() -> route('dashboard.index')->with('message', 'You are now logged in!');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }
}
