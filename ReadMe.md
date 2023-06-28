请执行以下命令

```bash
cd tsapi
ln -s ts3phpframework/lib/TeamSpeak3 TeamSpeak3
ln -s ts3phpframework/images images
```

然后将TeamSpeak3文件夹下的`api.php` `ststus.php` `tree.php`中的TeamSpeak3::factory中的passwd等字样改为你teamspeak数据库的密码

将`tsapi`文件夹下的`index.html`中的入口的网址替换为你自己服务器的（当然，上面的php文件中也请一并替换，如果您用不到status和tree这两个php文件，请直接删除即可）

