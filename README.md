# 项目运行指导手册
## 准备工作
1. 项目部署需要用XAMPP，运行前得打开apache以及MySQL
2. php版本是8.0.30

## 部署步骤
1. 打开xampp的数据库入口创建数据库
数据库名：shop_campus
字符集：utf8mb4_unicode_ci

2. 命令行以管理员身份运行进入项目目录

3. 安装composer
composer install
php artisan key:generate

4. 跑数据库迁移 + 填充数据
打开 CMD，确保在项目目录：
执行：
php artisan migrate
php artisan db:seed

5. 安装前端依赖并编译
还是在这个目录：
npm install
npm run dev
如果还没装 Node.js，npm 会是未知命令，需要安装；如果能跑就直接等它完成。

6. 配置本地.env文件激活邮箱验证功能

把拉取的.env.example文件名称改成.env
修改以下内容：
MAIL_USERNAME=你的QQ邮箱
MAIL_PASSWORD=你的授权码
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=你的QQ邮箱

授权码获取方式：
登录QQ邮箱网页端，mail.qq.com，点右上角设置-账号与安全-安全设置
打开POP3/IMAP/SMTP/Exchange/CardDAV 服务，生成授权码，即可获得授权码
这样网站使用邮箱验证功能时就会从你的QQ邮箱为发送端发送验证邮件到用户

7. 启动网站服务
php.exe artisan serve
然后浏览器打开网址：http://localhost:8000（推荐）或 http://127.0.0.1:8000

8. 若有图片显示不全可以尝试以下命令
清理缓存以及重新数据库迁移 + 填充数据
php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh --seed



