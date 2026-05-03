<?php

use Encore\Admin\Facades\Admin;

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
//Encore\Admin\Form::forget(['map']);
Encore\Admin\Form::forget(['map', 'editor']);

// Apply custom admin theme to align with frontend style
Admin::css('/css/admin-custom.css');

// 在管理员登录页面添加返回普通用户登录页面的链接
// 直接在页面底部注入内联脚本，确保登录页面也能执行
Admin::script('
(function() {
    var path = window.location.pathname;
    if (path === "/admin/auth/login" || path.includes("/admin/auth/login")) {
        function addLink() {
            if (document.getElementById("user-login-link")) return;
            
            var targets = [".login-box-body", ".login-box", ".box-body", "form[action*=\"login\"]", "form[method=\"post\"]"];
            var target = null;
            for (var i = 0; i < targets.length; i++) {
                var el = document.querySelector(targets[i]);
                if (el) { target = el; break; }
            }
            
            var div = document.createElement("div");
            div.id = "user-login-link";
            div.style.cssText = "text-align: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid #e0e0e0;";
            div.innerHTML = \'<a href="/login" style="color: #2d4d63; font-weight: 600; font-size: 14px; text-decoration: none;"><i class="fa fa-arrow-left"></i> 返回用户登录</a>\';
            
            if (target) {
                if (target.tagName === "FORM") {
                    target.parentNode.insertBefore(div, target.nextSibling);
                } else {
                    target.appendChild(div);
                }
            } else {
                div.style.cssText = "position: fixed; bottom: 20px; right: 20px; z-index: 9999; background: white; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);";
                document.body.appendChild(div);
            }
        }
        
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", addLink);
        } else {
            addLink();
        }
        setTimeout(addLink, 100);
        setTimeout(addLink, 500);
        setTimeout(addLink, 1000);
    }
})();
');

// 注册菜单项 - Laravel Admin 会自动根据 AdminController 生成菜单
// 如果菜单未显示，请检查 config/admin.php 中的菜单配置
// 或者通过后台的"菜单管理"功能手动添加菜单项