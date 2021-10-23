## About
基于CMS搭建的鑫盈能源官网，layui前端框架实现，结合mysql与MongoDB数据库

## Feature
* 界面足够简洁清爽的CMS
* 官网内容模块支持新闻、QA、友情链接、分类
* 完善的后台模块，包括会员、用户、权限、菜单、配置、日志、redis等
* 后台支持无限级菜单
* 邮件通知支持异步队列，提高用户体验
* 接口支持jwt与参数签名，强化安全性
* 日志支持MongoDB与mysql存储
* 基于layui编写的最简单、易用的后台框架模板

## Requires
PHP 7.2 or Higher  

## Install
```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan sorage:link
```

## Usage
```
# Login Admin
username: admin
password: 123456
```

## Change Log
v1.0.1 - 2021-10-04
* 添加会员管理、会员等级、登入日志功能
* 添加新闻管理、新闻分类功能
* 添加QA管理、QA分类功能
* 添加友情链接、H5管理功能
* 添加用户管理、用户组别功能
* 添加菜单管理功能
* 添加权限管理、权限组别功能
* 添加操作日志、登入日志、api访问日志功能
* 添加配置功能
* 添加redis键值管理、redis服务器信息功能
* 接口添加参数签名与jwt认证机制

## Maintainers
Alan

## LICENSE
MIT License
