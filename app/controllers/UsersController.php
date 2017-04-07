<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2016, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

/**
 * This controller is used for serving static pages by name, which are located in the `/views/pages`
 * folder.
 *
 * A Lithium application's default routing provides for automatically routing and rendering
 * static pages using this controller. The default route (`/`) will render the `home` template, as
 * specified in the `view()` action.
 *
 * Additionally, any other static templates in `/views/pages` can be called by name in the URL. For
 * example, browsing to `/pages/about` will render `/views/pages/about.html.php`, if it exists.
 *
 * Templates can be nested within directories as well, which will automatically be accounted for.
 * For example, browsing to `/pages/about/company` will render
 * `/views/pages/about/company.html.php`.
 */

use app\models\Users;
use lithium\security\Auth;
use lithium\storage\Session;

class UsersController extends \lithium\action\Controller {

	private $_userSessionKey = 'user_hash_key';

	public function create() {

		$this->request->data['password'] = md5($this->request->data['password']);
		$user = Users::create($this->request->data);
		$user->save();

		$this->redirect(['Pages::success', 'id' => $user->id]);
	}

	public function checkInfo () {

		$user = Users::find('first', [
				'conditions' => [
					'email' => $this->request->args[0]
				]
			]);

		$ret = [];

		if ($user) {
			$ret['email'] = [
					'taken' => true,
					'message' => 'Email Already Taken'
				];
		} else {
			$ret['email'] = [
					'taken' => false,
					'message' => 'Email Available'
				];
		}

		$user = Users::find('first', [
				'conditions' => [
					'username' => $this->request->args[1]
				]
			]);

		if ($user) {
			$ret['username'] = [
					'taken' => true,
					'message' => 'Username Already Taken'
				];
		} else {
			$ret['username'] = [
					'taken' => false,
					'message' => 'Username Available'
				];
		}

		echo json_encode($ret);
		
		die();
	}

	public function checklogin () {

		$user = Users::find('first', [
				'conditions' => [
					'username' => $this->request->args[0],
					'password' => md5($this->request->args[1])
				]
			]);

		if ($user) {
			$this->setSession($user);
			echo json_encode([
					'status' => 'passed',
				]);
		} else {
			echo json_encode([
					'status' => 'failed',
				]);
		}
		die();
	}

	public function login () {

		$user = Users::find('first', [
			'conditions' => [
				'username' => $this->request->data['username'],
				'password' => md5($this->request->data['password'])
			]
		]);

		if (!$this->checkSession($user)) {
			$this->redirect(['Pages::view']);
		} else {
			$this->render([
				'template' => 'dashboard',
				'data' => [
					'user' => $user
				]
			]);
		}
	}

	public function logout () {
		// Auth::clear('users');
		$this->deleteSession();
        return $this->redirect(['Pages::view']);
	}

	private function setSession ($user) {
		$hash = base64_encode($user->id . "-" . $user->email);
		Session::write($this->_userSessionKey, $hash);
	}

	private function checkSession ($user) {
		$hashed = base64_decode(Session::read($this->_userSessionKey));
		$parts = explode("-",$hashed);
		return ($user->id == $parts[0] && $user->email == $parts[1]);
	}

	private function deleteSession () {
		Session::delete($this->_userSessionKey);
	}
}