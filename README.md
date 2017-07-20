
# 仿京东在线购物商城(PHP实现)

### 1.用户模块
```
# 注册登录功能
register.php 验证码类：session储存数字验证码，code.php, vcode.class.php
login.php 保存密码：setcookie
logout.php 注销用户: 设置cookie过期

# 用户个人中心
member.php 查看个人资料
member_modify.php 修改个人资料

# 后台操作用户列表
manager.php 查看后台管理信息
manage_member.php 批量删除：js全选单选，分页
```


### 2.商品模块
**商品种类**
```
# 商品种类 tg_dir：主键 tg_id　商品目录 tg_dir
good.php 商品种类展示页，查看商品种类，管理员可以添加、修改、删除商品种类


# 管理员添加商品种类，并在服务器项目中创建一个商品种类目录，用来储存商品图片
good_add_dir.php 
# 管理员修改商品种类
good_modify_dir.php
# 商品种类目录的删除需要注意是否是非空目录，非空目录不能删除

```

**商品展示**
```
# 商品种类表：tg_dir，商品详情表：good
good_show.php 商品展示页：浏览商品缩略图thumb.php。管理员可以添加商品、修改、删除商品

# 添加商品页：点击上传链接，在js中定义缩略图参数，并通过window.open()打开upimg.php页面
# 并且上传商品图片到服务器临时目录，并移动到项目对应的商品种类目录
good_add_img.php->good_add_img.js->upimg.php

# 
good_detail.php 商品详情页：添加商品到购物车

# 删除商品：先删除服务器上的商品图片，再删除数据库中对应的商品信息

# 注：这里商品种类表还可以用到商品种类的无限级种类表
```

### 购物车模块
```
# 购物车表：buycar
buycar.php
涉及到数据库表的关联查询
buycar表 中的 goodId 和 good 表中 gooId 可以通过 inner join 查询出商品的信息和数量

# 购物车中商品的删除，还有全选

# ajax动态显示商品信息(buycar.js)
可以优化购物车通过 ajax 异步不刷新页面实现数据改动
```


### 订单模块
```
# 订单表：order_two

# 确认订单页面
notorder.php 确认订单页面：用户点击提交订单后将购物车中选中的商品存入订单表 order_two，然后再将这些商品从购物车中删除掉。
涉及到数据表的关联查询

order.php 订单显示页面
涉及到多表的关联查询
```

