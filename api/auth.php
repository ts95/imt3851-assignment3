<?php

require_once __DIR__ . '/../tools/Tools.php';

$this->respond('GET', '/[i:id]', function($request, $response, $service, $app) {
    $user = $app->db->from('user', $request->id)->fetch();
    $response->json($user);
});

$this->respond('POST', '/login', function($request, $response, $service, $app) {
    $email = $request->param('email');
    $rememberMe = $request->param('rememberMe', false);

    $user = $app->db->from('user')->where('email', $email)->fetch();

    $validator = new \Tools\Validator($_POST);

    $userValidator = function($value, $params, $fail) use($user) {
        if (!$user || !password_verify($params['password'], $user->password))
            $fail("Invalid credentials.");
    };

    $validator->validate('email', $userValidator);
    $validator->validate('password', $userValidator);

    if ($validator->hasErrors()) {
        $response->json([
            'ok' => false,
            'errors' => $validator->getErrors(),
        ]);
        return;
    }

    if ($rememberMe) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            $_COOKIE[session_name()],
            time() + 60*60*24*30,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    $app->auth->logIn($user);

    unset($user->password);

    $response->json([
        'ok' => true,
        'user' => $user,
    ]);
});

$this->respond('POST', '/register', function($request, $response, $service, $app) {
    $validator = new \Tools\Validator($_POST);

    $validator->validate('email', function($email, $params, $fail) use($app) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $fail("Invalid E-Mail.");

        if (mb_strlen($email) > 30)
            $fail("Maximum E-Mail length is 30 characters.");

        $user = $app->db->from('user')->where('email', $email)->fetch();

        if ($user)
            $fail("This E-Mail is already in use.");
    });

    $validator->validate('fullName', function($fullName, $params, $fail) {
        if (mb_strlen($fullName) > 30)
            $fail("Maximum name length is 30 characters.");

        if (mb_strlen($fullName) < 3)
            $fail("Minimum name length is 3 characters.");

        if (mb_strlen($fullName) >= 3 && \Tools\Helper::wordCount($fullName) < 2)
            $fail("Only one name detected. Please enter your full name.");
    });

    $validator->validate('password', function($password, $params, $fail) {
        if (!$password)
            $fail("Password required.");

        if (mb_strlen($password) < 3)
            $fail("Minimum password length is 3 characters.");
    });

    $validator->validate('repeatPassword', function($repeatPassword, $params, $fail) {
        if (!$repeatPassword)
            $fail("You must repeat the password.");

        if ($repeatPassword !== $params['password'])
            $fail("The repeated password must be equal to the first one.");
    });

    if ($validator->hasErrors()) {
        $response->json([
            'ok' => false,
            'errors' => $validator->getErrors(),
        ]);
        return;
    }

    $app->db->insertInto('user', [
        'email' => $request->param('email'),
        'full_name' => $request->param('fullName'),
        'password' => password_hash($request->param('password'), PASSWORD_DEFAULT),
        'admin' => $app->auth->admin ? (integer) $request->param('admin', 0) : 0,
    ])->execute();

    $user = $app->db->from('safe_user')->where('id', $app->db->getPDO()->lastInsertId())->fetch();

    $app->auth->logIn($user);

    $response->json([
        'ok' => true,
        'user' => $user,
    ]);
});

$this->respond('GET', '/logout', function($request, $response, $service, $app) {
    $app->auth->logOut();

    $response->json([
        'ok' => true,
    ]);
});
