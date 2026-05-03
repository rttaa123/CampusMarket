<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectAdminLoginLink
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        // 只在管理员登录页面注入脚本
        if ($request->is('admin/auth/login')) {
            $content = $response->getContent();
            
            // 在 </body> 标签前注入脚本
            $script = '<script>
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
</script>';
            
            $content = str_replace('</body>', $script . '</body>', $content);
            $response->setContent($content);
        }
        
        return $response;
    }
}
