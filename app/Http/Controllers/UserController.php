<?php

namespace Codice\Http\Controllers;

use App;
use Auth;
use Codice\Plugins\Action;
use Codice\User;
use Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use Redirect;
use View;

class UserController extends Controller
{
    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('guest', ['only' => 'getLogin']);
        $this->middleware('auth', ['except' => ['getLogin', 'postLogin', 'getEmail', 'postEmail', 'getReset', 'postReset']]);

        // Set redirectPath so that user is redirected correctly after the password reset
        $this->redirectTo = '/';
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return View::make('user.login', [
            'title' => trans('user.login.title'),
        ]);
    }

    /**
     * Process a login form.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        $this->validate($request, [
            'email' => 'email|required',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        $isValid = Auth::attempt($credentials, (bool) $request->input('remember'));

        /**
         * Executed on login attempt
         *
         * @since 0.4
         *
         * @param string $email E-mail address used to log in
         * @param bool $isValid Whether user logged in successfully
         * @param \Codice\User|null $user User object if credentials were correct, null otherwise
         */
        Action::call('user.login', [
            'email' => $credentials['email'],
            'isValid' => $isValid,
            'user' => $isValid ? Auth::user() : null,
        ]);

        if ($isValid) {
            return Redirect::intended(route('index'));
        }

        return Redirect::back()->with('message', trans('user.login.invalid'));
    }

    /**
     * Log user out
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();
        return Redirect::route('user.login');
    }

    /**
     * Display settings section for current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSettings()
    {
        return View::make('user.settings', [
            'languages' => config('app.languages'),
            'title' => trans('user.settings.title'),
            'user' => Auth::user(),
        ]);
    }

    /**
     * Process settings form.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSettings(Request $request)
    {
        $allowedLanguages = implode(',', array_keys(config('app.languages')));

        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password_new' => 'confirmed',
            'options.language' => "required|in:$allowedLanguages",
            'options.notes_per_page' => 'required|numeric',
        ]);

        // Set app's locale so correct message is displayed when language has
        // been just changed.
        App::setLocale($request->input('options')['language']);

        $message = trans('user.settings.success');

        $user = Auth::user();
        if ($request->has('password', 'password_new')) {
            if (!Hash::check($request->input('password'), $user->password)) {
                return Redirect::back()->with('message', trans('user.settings.password-wrong'))
                    ->with('message_type', 'danger');
            }

            $user->password = bcrypt($request->input('password_new'));
            $message = trans('user.settings.success-password');
        }
        $user->email = $request->input('email');
        $user->name = $request->input('name');

        // Cast types where necessary
        $optionsRequest = $request->input('options');
        $optionsRequest['wysiwyg'] = (bool) $optionsRequest['wysiwyg'];

        $user->options = $optionsRequest;
        $user->save();

        event('user.save', [$user]);

        return Redirect::back()->with('message', $message);
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail()
    {
        return view('auth.password');
    }

    /**
     * @inheritdoc
     */
    public function postEmail(Request $request)
    {
        // Not the best but the shortest way to localize sent emails properly.
        // Might consider overwriting Illuminate\Auth\Passwords\PasswordBroker
        // in the future or create fully customized implementation for resetting
        // passwords.

        $this->validate($request, ['email' => 'required|email']);

        try {
            $user = User::where('email', $request->input('email'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('status', trans('passwords.user'));
        }


        // It will also result in confirmation message  being localized but this
        // isn't a bad thing, right?
        App::setLocale($user->options['language']);

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));
            case Password::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('auth.reset')->with('token', $token);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect($this->redirectPath())->with('status', trans($response));
            default:
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }
}
