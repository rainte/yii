<?php

namespace Rainte\Yii\Models;

use Rainte\Yii\Exceptions\Exception;
use Yii;

class RegexValidator extends yii\validators\Validator
{
    /**
     * @var string|array 验证方法.
     */
    public $method;
    /**
     * @var array 验证的方法列表.
     */
    private $_methods = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->_methods = (array)$this->method;

        if (empty($this->_methods)) {
            Exception::fail('no validating method are found.');
        }

        foreach ($this->_methods as $method) {
            if (!$this->hasMethod($method)) {
                Exception::fail($$method . ' does not exits.');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        $this->message = (array)$this->message;

        foreach ($this->_methods  as $key => $method) {
            if (call_user_func([$this, $method], $value) === false) {
                $error = $this->message[$key] ?? Yii::t('yii', '{attribute} is invalid.');
                $this->addError($model, $attribute, $error);
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        $this->message = (array)$this->message;

        foreach ($this->_methods as $key => $method) {
            if (call_user_func([$this, $method], $value) === false) {
                $error = $this->message[$key] ?? Yii::t('yii', "\"{$value}\" is invalid specified by the validator:" . static::class . "::$method");

                return [$error, []];
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
    }

    /**
     * 验证字符串是否由数字和 26 个英文字母组成.
     * 
     * @param string $str 字符串.
     * @return bool
     **/
    public static function numschars(string $str): bool
    {
        $pattern = "/^[A-Za-z0-9]+$/";

        return preg_match($pattern, $str);
    }

    /**
     * 验证手机号码.
     * 
     * @param string $str 字符串.
     * @return bool
     **/
    public static function mobile(string $str): bool
    {
        $pattern = "/^1[3456789]{1}\d{9}$/";

        return preg_match($pattern, $str);
    }

    /**
     * 验证电子邮箱.
     * 
     * @param string $str 字符串.
     * @return bool
     **/
    public static function email(string $str): bool
    {
        return (bool)filter_var($str, FILTER_VALIDATE_EMAIL);
    }

    /**
     * 验证邮编.
     * 
     * @param string $str 字符串.
     * @return bool
     **/
    public static function zipcode(string $str): bool
    {
        $pattern = "/^[1-9]\d{5}(?!\d)$/";

        return preg_match($pattern, $str);
    }

    /**
     * 验证中文.
     * 
     * @param string $str 字符串.
     * @return bool
     **/
    public static function zh(string $str): bool
    {
        $pattern = "/^[\x{4e00}-\x{9fa5}]+$/u";

        return preg_match($pattern, $str);
    }

    /**
     * 验证 URL 地址.
     * 
     * @param string $str 字符串.
     * @return bool
     **/
    public static function url(string $str): bool
    {
        return (bool)filter_var($str, FILTER_VALIDATE_URL);
    }

    /**
     * 验证身份证.
     * 
     * @param string $str 字符串.
     * @return bool
     **/
    public static function identity(string $str): bool
    {
        $pattern = "/^(^\d{15}$)|(^\d{17}([0-9]|X)$)$/";

        return preg_match($pattern, $str);
    }

    /**
     * 验证 IPv4.
     * 
     * @param string $str 字符串.
     * @return bool
     **/
    public static function ip(string $str): bool
    {
        return (bool)filter_var($str, FILTER_VALIDATE_IP);
    }
}
