(function() {
    function addUserLoginLink() {
        var path = window.location.pathname;
        if ((path === "/admin/auth/login" || path.includes("/admin/auth/login")) && !document.getElementById("user-login-link")) {
            // 尝试多种选择器找到登录表单容器
            var targets = [
                ".login-box-body",
                ".login-box",
                ".box-body",
                "form[action*=\"login\"]",
                "form[method=\"post\"]",
                ".login-page .login-box",
                "body.login-page"
            ];
            
            var target = null;
            for (var i = 0; i < targets.length; i++) {
                var el = document.querySelector(targets[i]);
                if (el) {
                    target = el;
                    break;
                }
            }
            
            var linkDiv = document.createElement("div");
            linkDiv.id = "user-login-link";
            linkDiv.className = "user-login-link";
            linkDiv.style.cssText = "text-align: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid #e0e0e0;";
            linkDiv.innerHTML = '<a href="/login" style="color: #2d4d63; font-weight: 600; font-size: 14px; text-decoration: none;"><i class="fa fa-arrow-left"></i> 返回用户登录</a>';
            
            if (target) {
                if (target.tagName === "FORM") {
                    target.parentNode.insertBefore(linkDiv, target.nextSibling);
                } else {
                    target.appendChild(linkDiv);
                }
            } else {
                // 如果找不到目标，添加到 body 底部
                linkDiv.style.cssText = "position: fixed; bottom: 20px; right: 20px; z-index: 9999; background: white; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);";
                document.body.appendChild(linkDiv);
            }
        }
    }
    
    // 立即执行
    addUserLoginLink();
    
    // DOM 加载完成后执行
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", addUserLoginLink);
    }
    
    // 延迟执行多次，确保能插入
    setTimeout(addUserLoginLink, 100);
    setTimeout(addUserLoginLink, 500);
    setTimeout(addUserLoginLink, 1000);
    setTimeout(addUserLoginLink, 2000);
})();

