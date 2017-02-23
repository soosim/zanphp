<?php

use Zan\Framework\Utilities\Types\Arr;


if (! function_exists('data_set')) {
    /**
     * Set an item on an array or object using dot notation.
     *
     * @param  mixed  $target
     * @param  string|array  $key
     * @param  mixed  $value
     * @param  bool  $overwrite
     * @return mixed
     */
    function data_set(&$target, $key, $value, $overwrite = true)
    {
        $segments = is_array($key) ? $key : explode('.', $key);

        if (($segment = array_shift($segments)) === '*') {
            if (! Arr::accessible($target)) {
                $target = [];
            }

            if ($segments) {
                foreach ($target as &$inner) {
                    data_set($inner, $segments, $value, $overwrite);
                }
            } elseif ($overwrite) {
                foreach ($target as &$inner) {
                    $inner = $value;
                }
            }
        } elseif (Arr::accessible($target)) {
            if ($segments) {
                if (! Arr::exists($target, $segment)) {
                    $target[$segment] = [];
                }

                data_set($target[$segment], $segments, $value, $overwrite);
            } elseif ($overwrite || ! Arr::exists($target, $segment)) {
                $target[$segment] = $value;
            }
        } elseif (is_object($target)) {
            if ($segments) {
                if (! isset($target->{$segment})) {
                    $target->{$segment} = [];
                }

                data_set($target->{$segment}, $segments, $value, $overwrite);
            } elseif ($overwrite || ! isset($target->{$segment})) {
                $target->{$segment} = $value;
            }
        }

        return $target;
    }
}

if (! function_exists('sys_echo')) {
    function sys_echo($context) {
        $dataStr = date("Y-m-d H:i:s", time());
        echo "[$dataStr] $context\n";
    }
}

if (! function_exists('sys_error')) {
    function sys_error($context) {
        $dataStr = date("Y-m-d H:i:s", time());
        fprintf(STDERR, "[$dataStr] $context\n");
    }
}

if (! function_exists('echo_exception')) {
    function echo_exception(\Exception $e)
    {
        $time = date('Y-m-d H:i:s', time());
        $code = $e->getCode();
        $msg = $e->getMessage();
        $trace = $e->getTraceAsString();

        echo <<<EOF
        
        
###################################################################################
          \033[1;31mGot a exception\033[0m
          time: $time
          code: $code
          message: $msg
          
$trace
###################################################################################


EOF;
    }
}

if (! function_exists('dd')) {
    function dd()
    {
        if (func_num_args() === 0) {
            return;
        }

        // Get all passed variables
        $variables = func_get_args();
        var_dump($variables);
        die;
    }
}

if (! function_exists('d')) {
    function d()
    {
        if (func_num_args() === 0) {
            return;
        }

        // Get all passed variables
        $variables = func_get_args();
        var_dump($variables);
    }
}

if (! function_exists('swoole2x')) {
    function swoole2x() {
        return version_compare(phpversion('swoole'), '2.0.0') >= 0;
    }
}
