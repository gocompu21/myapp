<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct()
    {
        \Log::info("시작..");

        $this->middleware('guest',['except'=>'destroy']);
        \Log::info("시작2..");

    }

    public function create()
    {
        return view('sessions.create');
    }

    /**
     * Handle login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        \Log::info("시작3");

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $token = is_api_domain()
            ? jwt()->attempt($request->only('email', 'password'))
            : auth()->attempt($request->only('email', 'password'), $request->has('remember'));


        if (! $token) {
            return $this->respondLoginFailed();
        }

        if (! auth()->user()->activated) {
            auth()->logout();
            return $this->respondNotConfirmed();
        }

        return $this->respondCreated($token);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {
        auth()->logout();
        flash(
            trans('auth.sessions.info_bye')
        );

        return redirect(route('root'));
    }

    /* Helpers */

    /* Response Methods */

    /**
     * Make a success response.
     *
     * @param string|boolean $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondCreated($token)
    {
        flash(
            trans('auth.sessions.info_welcome', ['name' => auth()->user()->name])
        );

        return ($return = request('return'))
            ? redirect(urldecode($return))
            : redirect()->intended(route('home'));
    }

    /**
     * Make an error response.
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function respondError($message)
    {
        flash()->error($message);

        return back()->withInput();
    }

    /**
     * @return $this
     */
    protected function respondSocialUser()
    {
        flash()->error(
            trans('auth.sessions.error_social_user')
        );

        return back()->withInput();
    }

    /**
     * @return $this
     */
    protected function respondLoginFailed()
    {
        flash()->error(
            trans('auth.sessions.error_incorrect_credentials')
        );

        return back()->withInput();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function respondNotConfirmed()
    {
        flash()->error(
            trans('auth.sessions.error_not_confirmed')
        );

        return back()->withInput();
    }
}
