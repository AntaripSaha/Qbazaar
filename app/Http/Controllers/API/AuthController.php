<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Utilities\Overrider;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Socialite;

class AuthController extends Controller {

	/**
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('auth:api', ['except' => ['login', 'register', 'social_login']]);
	}

	/**
	 * Get a JWT token via given credentials.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login(Request $request) {
		$credentials = [
			'email' => $request->email,
			'password' => $request->password,
			'user_type' => ['customer', 'delivery_boy'],
		];

		if ($token = $this->guard()->attempt($credentials)) {
			return $this->respondWithToken($token);
		}

		return response()->json(['result' => false, 'message' => _lang('Username or Password is incorrect')], 401);
	}

	/**
	 * Register New User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register(Request $request) {
		$request->validate([
			'name' => 'required|string|max:191',
			'email' => 'required|string|email|max:191|unique:users',
			'password' => 'required|string|min:6|confirmed',
			'phone' => 'required',
		], [
			'name.required' => _lang('The name field is required.'),
			'email.required' => _lang('The email field is required.'),
			'email.unique' => _lang('Email address already exists.'),
			'password.required' => _lang('The password field is required.'),
			'password.min' => _lang('The password must be at least 6 characters.'),
			'password.confirmed' => _lang('The password confirmation does not match.'),
			'phone.required' => _lang('The phone field is required.'),
		]);

		$user = new User();
		$user->name = $request->name;
		$user->email = $request->email;
		$user->phone = $request->phone;
		$user->email_verified_at = get_option('email_verification') != 'enabled' ? now() : NULL;
		$user->user_type = 'customer';
		$user->password = Hash::make($request->password);
		$user->status = 1;
		$user->save();

		$credentials = $request->only('email', 'password');
		$token = $this->guard()->attempt($credentials);

		if (get_option('email_verification') == 'enabled') {
			//Send Email Verification Message
			Overrider::load("Settings");
			$this->guard()->user()->sendEmailVerificationNotification();
		} else {
			//Trigger Verified Event
			event(new \Illuminate\Auth\Events\Verified($user));
		}

		return $this->respondWithToken($token);

	}

	/**
	 * Get the authenticated User
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function profile() {
		return new UserResource($this->guard()->user());
	}

	/**
	 * Update user information
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update_profile(Request $request) {
		$user = $this->guard()->user();

		$request->validate([
			'name' => 'required|string|max:191',
			'email' => [
				'required',
				'email',
				Rule::unique('users')->ignore($user->id),
			],
			'password' => 'nullable|string|min:6|confirmed',
			'phone' => 'required',
			'address' => 'required',
		]);

		$user->name = $request->name;
		$user->email = $request->email;
		$user->phone = $request->phone;
		$user->address = $request->address;
		if ($request->password) {
			$user->password = Hash::make($request->password);
		}
		$user->save();

		return new UserResource($user);
	}

	/**
	 * Update profile picture.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update_profile_picture(Request $request) {
		$request->validate([
			'profile_picture' => 'nullable|image|max:2048',
		]);

		if ($request->hasFile('profile_picture')) {
			$image = $request->file('profile_picture');
			$file_name = "profile_" . time() . '.' . $image->getClientOriginalExtension();
			$image->move(base_path('public/uploads/profile/'), $file_name);
			$user = $this->guard()->user();
			$user->profile_picture = $file_name;
			$user->save();

			return new UserResource($user);
		}

	}

	/**
	 * Log the user out (Invalidate the token)
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout() {
		$this->guard()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh() {
		return $this->respondWithToken($this->guard()->refresh());
	}

	public function request_verification_link() {
		//Send Email Verification Message
		Overrider::load("Settings");
		$this->guard()->user()->sendEmailVerificationNotification();
	}

	/**
	 * Social Login
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function social_login($provider) {
		Overrider::load("SocialSettings");
		$userSocial = Socialite::driver($provider)->redirectUrl(url(''))->stateless()->user();
		$user = User::where(['email' => $userSocial->getEmail()])->first();

		if ($user) {
			$token = $this->guard()->login($user);
			return $this->respondWithToken($token);
		} else {
			$profile_picture = "default.png";
			if ($userSocial->getAvatar() != "") {
				$fileContents = file_get_contents($userSocial->getAvatar());
				$profile_picture = time() . "_avatar.jpg";
				$path = public_path() . '/uploads/profile/' . $profile_picture;
				\File::put($path, $fileContents);
			}

			$user = new User();
			$user->name = $userSocial->getName();
			$user->email = $userSocial->getEmail();
			$user->user_type = 'customer';
			$user->status = 1;
			$user->profile_picture = $profile_picture;
			$user->email_verified_at = now();
			$user->provider_id = $userSocial->getId();
			$user->provider = $provider;
			$user->save();

			$token = $this->guard()->login($user);
			return $this->respondWithToken($token);
		}
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token) {
		return response()->json([
			'result' => true,
			'token' => $token,
			'user' => new UserResource($this->guard()->user()),
			'token_type' => 'Bearer',
			'expires_in' => $this->guard()->factory()->getTTL() * 60,
		]);
	}

	/**
	 * Get the guard to be used during authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\Guard
	 */
	public function guard() {
		return Auth::Guard('api');
	}
}
