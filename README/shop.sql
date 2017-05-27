# Host: localhost  (Version 5.7.17)
# Date: 2017-03-11 17:03:01
# Generator: MySQL-Front 6.0  (Build 1.57)


#
# Structure for table "buycar"
#

DROP TABLE IF EXISTS `buycar`;
CREATE TABLE `buycar` (
  `bcId` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(8) unsigned NOT NULL,
  `goodId` int(8) unsigned NOT NULL,
  `goodCount` int(8) unsigned NOT NULL DEFAULT '0',
  `goodImg` varchar(200) NOT NULL,
  PRIMARY KEY (`bcId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

#
# Data for table "buycar"
#

INSERT INTO `buycar` VALUES (1,37,17,1,'photo/1467869228/20160708003139448.png'),(2,37,13,1,'photo/1463140658/20160706173852942.png');

#
# Structure for table "good"
#

DROP TABLE IF EXISTS `good`;
CREATE TABLE `good` (
  `goodId` int(8) NOT NULL AUTO_INCREMENT,
  `goodName` varchar(20) NOT NULL,
  `goodPrice` float NOT NULL,
  `goodType` varchar(20) NOT NULL,
  `goodContent` varchar(200) NOT NULL,
  `goodImg` varchar(40) NOT NULL,
  `goodMadePlace` varchar(40) NOT NULL,
  `goodBuyCount` int(8) NOT NULL DEFAULT '0',
  `goodCommentCount` int(8) NOT NULL DEFAULT '0',
  `goodSid` int(8) unsigned NOT NULL,
  `goodAddTime` datetime NOT NULL COMMENT '//商品添加时间',
  PRIMARY KEY (`goodId`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

#
# Data for table "good"
#

INSERT INTO `good` VALUES (5,'拖鞋',29.99,'生活用品','我的拖鞋是最好的！免邮哦！','photo/1463140658/20160706172731278.png','江西省九江市',0,0,1,'2016-07-06 17:27:56'),(6,'信封',4.5,'生活用品','优惠多多，欢迎光临！','photo/1463140658/20160706172851792.png','江西省宜春市',0,0,1,'2016-07-06 17:30:36'),(7,'刀叉',22.4,'生活用品','优惠多多！免邮！','photo/1463140658/20160706173100650.png','江西省南昌市',0,0,1,'2016-07-06 17:32:06'),(8,'木床',3430,'生活用品','优惠多多！免邮！','photo/1463140658/20160706173221598.png','江西省新余市',0,0,1,'2016-07-06 17:32:57'),(9,'节能灯',15,'生活用品','优惠多多！免邮！','photo/1463140658/20160706173329808.png','北京市密云区',0,0,1,'2016-07-06 17:34:01'),(10,'白炽灯',11.5,'生活用品','优惠多多！免邮！','photo/1463140658/20160706173524179.png','上海市浦东区',0,0,1,'2016-07-06 17:36:00'),(11,'墨镜',405,'生活用品','优惠多多！免邮！','photo/1463140658/20160706173643156.png','广东省深圳市',0,0,1,'2016-07-06 17:37:37'),(12,'自行车',1115.5,'生活用品','优惠多多！免邮！','photo/1463140658/20160706173752501.png','云南省昆明市',0,0,1,'2016-07-06 17:38:27'),(13,'邮票',1.1,'生活用品','优惠多多！免邮！','photo/1463140658/20160706173852942.png','江西省九江市',0,0,1,'2016-07-06 17:39:17'),(14,'音响',4350,'图书音像','我的音响，音质好，价格便宜！','photo/1463140760/20160706174007343.png','北京市房山区',0,0,2,'2016-07-06 17:41:05'),(16,'冰箱',4350,'家用电器','格力冰箱！优惠多多！','photo/1467797135/20160707105349587.png','江苏苏州',0,0,6,'2016-07-07 10:54:08'),(17,'炫酷装逼墨镜',22.68,'服装鞋靴','我的墨镜最装逼了，快来快来买一买嘞！','photo/1467869228/20160708003139448.png','江西省九江市',0,0,7,'2016-07-08 00:32:59');

#
# Structure for table "order_two"
#

DROP TABLE IF EXISTS `order_two`;
CREATE TABLE `order_two` (
  `orderId` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(8) unsigned NOT NULL,
  `goodIds` varchar(200) NOT NULL,
  `goodCounts` varchar(200) NOT NULL,
  `payMethod` varchar(20) NOT NULL,
  `orderDate` datetime NOT NULL,
  PRIMARY KEY (`orderId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

#
# Data for table "order_two"
#

INSERT INTO `order_two` VALUES (3,30,'11,13,9','1,1,2','货到付款','2016-07-08 20:19:37'),(4,30,'6,8,9','1,1,2','货到付款','2016-07-08 21:32:27'),(5,27,'16,11,12','2,1,1','货到付款','2017-03-11 16:31:40'),(6,27,'7,5','1,1','货到付款','2017-03-11 16:33:03');

#
# Structure for table "tg_dir"
#

DROP TABLE IF EXISTS `tg_dir`;
CREATE TABLE `tg_dir` (
  `tg_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '//ID',
  `tg_type` varchar(20) NOT NULL COMMENT '//相册类型',
  `tg_content` varchar(200) DEFAULT NULL COMMENT '//相册描述',
  `tg_face` varchar(200) DEFAULT NULL COMMENT '//相册目录封面',
  `tg_dir` varchar(200) NOT NULL COMMENT '//相册物理地址',
  `tg_date` datetime NOT NULL COMMENT '//相册创建时间',
  PRIMARY KEY (`tg_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

#
# Data for table "tg_dir"
#

INSERT INTO `tg_dir` VALUES (1,'生活用品','祝您生活愉快！','photo/monipic/face1.png','photo/1463140658','2016-05-13 19:57:38'),(2,'图书音像','祝您学习越来越好！','photo/monipic/face2.png','photo/1463140760','2016-05-13 19:59:20'),(6,'家用电器','祝您生活越来越方便！','photo/monipic/face5.png','photo/1467797135','2016-07-06 17:25:35'),(7,'服装鞋靴','祝你越变月美丽！','photo/monipic/face3.png','photo/1467869228','2016-07-07 13:27:08'),(8,'手机数码','祝您生活越来越充实！','photo/monipic/face4.png','photo/1467869300','2016-07-07 13:28:20'),(9,'食品酒类','祝您越吃越开心！','photo/monipic/face6.png','photo/1467869345','2016-07-07 13:29:05'),(10,'手表箱包','祝您越来越时尚！','photo/monipic/face1.png','photo/1467869715','2016-07-07 13:35:15');

#
# Structure for table "user"
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userid` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `sex` char(1) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `address` varchar(40) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `email` varchar(40) NOT NULL,
  `reg_time` datetime NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

#
# Data for table "user"
#

INSERT INTO `user` VALUES (27,'gzhwwj','7c4a8d09ca3762af61e59520943dc26494f8941b','男',1,'江西省九江市','1533334488','151@163.com','2016-07-01 12:00:00'),(28,'wwjgzh','7c4a8d09ca3762af61e59520943dc26494f8941b','女',0,'江西省九江市开发区','1511112222','151@gmail.com','2016-07-01 12:05:20'),(29,'高忠欢','7c4a8d09ca3762af61e59520943dc26494f8941b','男',0,'江西省九江市','15133331111','151@gmail.com','2016-07-01 13:00:00'),(30,'九九','7c4a8d09ca3762af61e59520943dc26494f8941b','女',0,'江西省九江市开发区','1511112222','151@gmail.com','2016-07-01 13:34:00'),(32,'八八','7c4a8d09ca3762af61e59520943dc26494f8941b','女',0,'江西省九江市开发区','1511112222','151@gmail.com','2016-07-05 15:34:23'),(34,'七七','7c4a8d09ca3762af61e59520943dc26494f8941b','女',0,'江西省宜春市','15188886666','1158862801@qq.com','2016-07-05 17:03:52'),(36,'六六','7c4a8d09ca3762af61e59520943dc26494f8941b','女',0,'江西省九江市','15188886666','151@163.com','2016-07-07 16:39:12'),(37,'一一','7c4a8d09ca3762af61e59520943dc26494f8941b','男',0,'九江市','15188886666','15180628419@163.com','2016-07-09 10:14:39');
