<?php

class Auth_AuthController extends BaseController {
    const PASSWORD_COST = 11; // 这里配置bcrypt算法的代价，根据需要来随时升级
    const PASSWORD_ALGO = PASSWORD_BCRYPT; // 默认使用（现在也只能用）bcrypt
    const SALT          = 'usesomesillystringforsalt';

    /*public function init($hash){
    $this->password_hash = $hash;
    }*/

    /**
     * 验证密码是否正确
     *
     * @param string $plainPassword 用户密码的明文
     * @param bool $autoRehash 是否自动重新计算下密码的hash值（如果有必要的话）
     * @return bool
     */
    protected function verifyPassword($plainPassword, $hash, $uid = '', array $autoRehash = []) {
        //验证密码hash
        if (password_verify($plainPassword, $hash)) {
            //修改/加密算法/计算难度/盐值
            if (!empty($autoRehash)) {
                $password_algo   = isset($autoRehash['algo']) ? $autoRehash['algo'] : self::PASSWORD_ALGO;
                $options['cost'] = isset($autoRehash['options']['cost']) ? $autoRehash['options']['cost'] : self::PASSWORD_COST;
                $options['salt'] = isset($autoRehash['options']['salt']) ? $autoRehash['options']['salt'] : self::SALT;
                if (password_needs_rehash($hash, $password_algo, $options)) {
                    return $this->updatePassword($plainPassword, $uid, $autoRehash);
                }
            }
            return true;
        }
        return false;
    }

    final protected function mcryptPass($passwd, array $password_option = []) {
        if (isset($passwd) && $passwd != '') {
            $password_algo   = isset($password_option['algo']) ? $password_option['algo'] : self::PASSWORD_ALGO;
            $options['cost'] = isset($password_option['options']['cost']) ? $password_option['options']['cost'] : self::PASSWORD_COST;
            $options['salt'] = isset($password_option['options']['salt']) ? $password_option['options']['salt'] : self::SALT;

            return password_hash($passwd, $password_algo, $options);
        } else {
            return false;
        }
    }

    final protected function md5Pass($passwd) {
        if (isset($passwd) && !empty($passwd)) {
            return md5($passwd);
        } else {
            return false;
        }
    }

    /**
     * 更新密码
     *
     * @param string $newPlainPassword
     */
    final protected function updatePassword($newPlainPassword, $uid, $options) {
        $password_hash = $this->mcryptPass($newPlainPassword, $options);
        if ($password_hash && $uid) {
            $user = parent::$db_resouce->find('UsersModel', ['Id' => $uid]);
            $user->setUserPass($password_hash);
            parent::$db_resouce->flush();
            $this->dd($user->getId());
        }
    }
}
