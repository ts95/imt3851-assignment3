<?php

namespace Tools;

class Validator {

    private $params;
    private $errors;

    public function __construct($params, $files = []) {
        $this->params = array_map(function($param) {
            return gettype($param) === 'string' ? trim($param) : $param;
        }, $params);

        foreach ($files as $name => $file) {
            $this->params[$name] = [];

            $c = count($file['name']);

            for ($i = 0; $i < $c; $i++) {
                $this->params[$name][] = [
                    'error' => $file['error'][$i],
                    'name' => $file['name'][$i],
                    'size' => $file['size'][$i],
                    'tmp_name' => $file['tmp_name'][$i],
                    'type' => $file['type'][$i],
                ];
            }
        }

        $this->errors = [];
    }

    public function validate($name, $cb) {
        if (isset($this->params[$name])) {
            $messages = [];

            $cb($this->params[$name], $this->params, function($message) use(&$messages) {
                $messages[] = $message;
            });

            if (count($messages) > 0)
                $this->errors[$name] = $messages;
        } else {
            $messages = [];

            $cb([], $this->params, function($message) use(&$messages) {
                $messages[] = $message;
            });

            if (count($messages) > 0)
                $this->errors[$name] = $messages;
        }
    }

    public function hasErrors() {
        return count($this->errors) > 0;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getParam($name) {
        return $this->params[$name];
    }
}
