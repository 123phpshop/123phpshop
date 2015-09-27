-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 09 月 26 日 07:52
-- 服务器版本: 5.5.27
-- PHP 版本: 5.4.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `sql_123phpshop`
--

-- --------------------------------------------------------

--
-- 表的结构 `ad`
--

CREATE TABLE IF NOT EXISTS `ad` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `image_width` int(10) unsigned NOT NULL,
  `image_height` int(10) unsigned NOT NULL,
  `name` varchar(32) NOT NULL COMMENT '广告名称',
  `intro` text NOT NULL COMMENT '介绍',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='广告表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ad`
--

INSERT INTO `ad` (`id`, `image_width`, `image_height`, `name`, `intro`, `create_time`, `is_delete`) VALUES
(1, 746, 465, '首页Banner广告', '首页广告轮', '2015-08-06 02:59:07', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ad_images`
--

CREATE TABLE IF NOT EXISTS `ad_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ad_id` int(10) unsigned NOT NULL COMMENT '所对应的广告的id',
  `image_path` varchar(100) NOT NULL COMMENT '图片地址',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `link_url` varchar(100) NOT NULL COMMENT '链接的url',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='广告图片列表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `ad_images`
--

INSERT INTO `ad_images` (`id`, `ad_id`, `image_path`, `create_time`, `link_url`) VALUES
(1, 1, '/uploads/ad/20150908105209_737.png', '2015-09-08 08:52:09', 'http://www.123phpshop.com');

-- --------------------------------------------------------

--
-- 表的结构 `catalog`
--

CREATE TABLE IF NOT EXISTS `catalog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `product_num` int(11) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `catalog`
--

INSERT INTO `catalog` (`id`, `name`, `pid`, `product_num`, `is_delete`) VALUES
(1, '默认分类', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `express_company`
--

CREATE TABLE IF NOT EXISTS `express_company` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(200) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `disabled` enum('true','false') DEFAULT 'false',
  `ordernum` smallint(4) unsigned DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `request_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ind_disabled` (`disabled`) USING BTREE,
  KEY `ind_ordernum` (`ordernum`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- 转存表中的数据 `express_company`
--

INSERT INTO `express_company` (`id`, `code`, `name`, `disabled`, `ordernum`, `website`, `request_url`) VALUES
(1, 'EMS', '中国邮政EMS', 'false', 1, 'http://www.ems.com.cn/', 'http://www.ems.com.cn/'),
(2, 'STO', '申通快递', 'false', 2, 'http://www.sto.cn/', 'http://www.sto.cn/'),
(3, 'YTO', '圆通速递', 'false', 3, 'http://www.yto.net.cn/', 'http://www.yto.net.cn/'),
(4, 'SF', '顺丰速运', 'false', 4, 'http://www.sf-express.com/', 'http://www.sf-express.com/'),
(5, 'YUNDA', '韵达快递', 'false', 5, 'http://www.yundaex.com/', 'http://www.yundaex.com/'),
(6, 'ZTO', '中通速递', 'false', 6, 'http://www.zto.cn/', 'http://www.zto.cn/'),
(7, 'ZJS', '宅急送', 'false', 7, 'http://www.zjs.com.cn/', 'http://www.zjs.com.cn/'),
(8, 'TTKDEX', '天天快递', 'false', 8, 'http://www.ttkd.cn/', 'http://www.ttkd.cn/'),
(9, 'LBEX', '龙邦快递', 'false', 9, 'http://www.lbex.com.cn/', 'http://www.lbex.com.cn/'),
(10, 'APEX', '全一快递', 'false', 10, 'http://www.apex100.com/', 'http://www.apex100.com/'),
(11, 'HTKY', '汇通速递', 'false', 11, 'http://www.htky365.com/', 'http://www.htky365.com/'),
(12, 'CNMH', '民航快递', 'false', 12, 'http://www.cae.com.cn/', 'http://www.cae.com.cn/'),
(13, 'AIRFEX', '亚风速递', 'false', 13, 'http://www.airfex.cn/', 'http://www.airfex.cn/'),
(14, 'CNKJ', '快捷速递', 'false', 14, 'http://www.fastexpress.com.cn/', 'http://www.fastexpress.com.cn/'),
(15, 'DDS', 'DDS快递', 'false', 15, 'http://www.qc-dds.net/', 'http://www.qc-dds.net/'),
(16, 'HOAU', '华宇物流', 'false', 16, 'http://www.hoau.net/', 'http://www.hoau.net/'),
(17, 'CRE', '中铁快运', 'false', 17, 'http://www.cre.cn/', 'http://www.cre.cn/'),
(18, 'FedEx', 'FedEx', 'false', 18, 'http://www.fedex.com/cn/', 'http://www.fedex.com/cn/'),
(19, 'UPS', 'UPS', 'false', 19, 'http://www.ups.com/', 'http://www.ups.com/'),
(20, 'DHL', 'DHL', 'false', 20, 'http://www.cn.dhl.com/', 'http://www.cn.dhl.com/'),
(21, 'CYEXP', '长宇', 'false', 20, 'http://www.cyexp.com/', 'http://www.cyexp.com/'),
(22, 'DBL', '德邦物流', 'false', 20, 'http://www.deppon.com/', 'http://www.deppon.com/'),
(23, 'POST', 'POST', 'false', 20, 'http://www.183yf.cn/', 'http://www.183yf.cn/'),
(24, 'CCES', 'CCES', 'false', 20, 'http://www.cces.com.cn/', 'http://www.cces.com.cn/'),
(25, 'DTW', '大田', 'false', 20, 'http://www.dtw.com.cn/', 'http://www.dtw.com.cn/'),
(26, 'ANTO', '安得', 'false', 20, 'http://www.annto.com/', 'http://www.annto.com/'),
(27, '其他', '其他', 'false', 50, '', ''),
(28, 'BAM', '平邮', 'false', 1, '', '');

-- --------------------------------------------------------

--
-- 表的结构 `friend_links`
--

CREATE TABLE IF NOT EXISTS `friend_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `link_text` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `link_image` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `register_at` varchar(255) DEFAULT NULL,
  `last_login_at` varchar(255) DEFAULT NULL,
  `mobile_confirmed` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `member`
--

INSERT INTO `member` (`id`, `username`, `password`, `mobile`, `email`, `register_at`, `last_login_at`, `mobile_confirmed`, `birth_date`, `is_delete`, `last_login_ip`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '13391334121', 'thomas@thoma.com', NULL, '2015-09-26 07:35:14', '1', NULL, 0, '127.0.0.1');

-- --------------------------------------------------------

--
-- 表的结构 `member_consignee`
--

CREATE TABLE IF NOT EXISTS `member_consignee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `province` varchar(11) DEFAULT NULL,
  `city` varchar(11) DEFAULT NULL,
  `distict` varchar(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `publish_time` datetime DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_delete` tinyint(255) NOT NULL DEFAULT '0',
  `clicks` int(255) NOT NULL DEFAULT '0',
  `from` varchar(255) DEFAULT NULL,
  `from_text` varchar(255) DEFAULT NULL,
  `catalog_id` int(11) NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `publish_time`, `create_time`, `is_delete`, `clicks`, `from`, `from_text`, `catalog_id`, `is_published`) VALUES
(1, '123PHPSHOP上线啦！', '<p>亲爱的用户：</p><p><br/></p><p>你好！</p><p>&nbsp; &nbsp;</p><p>&nbsp; &nbsp;欢迎使用123PHPSHOP。123PHPSHOP是一款由上海序程信息科技有限公司出品的，用最流行网站编程语言PHP语言编写的免费商城软件，您可以通过这个软件迅速搭建起来属于自己的商城系统。除了这款免费软件之外，序程信息科技有限公司还提供企业级的服务支持，详细请垂询13391334121，或是访问官网网站的相关页面：http://www.123phpshop.com/services.php.希望您使用愉快！</p><p style="text-align: center;"><img src="/uploads/image/20150926/1443246255296316.png" title="1443246255296316.png" alt="index_banner.png"/></p>', '2015-09-26 07:51:10', '2015-09-26 03:47:54', 0, 0, 'http://123phpshop', '本站', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `news_catalog`
--

CREATE TABLE IF NOT EXISTS `news_catalog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `news_catalog`
--

INSERT INTO `news_catalog` (`id`, `name`, `pid`, `is_delete`) VALUES
(1, '网站公告', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `should_paid` decimal(10,2) NOT NULL,
  `actual_paid` decimal(10,2) NOT NULL,
  `order_status` smallint(3) NOT NULL DEFAULT '0' COMMENT '-300已经退款-200经退货-100已经撤销0未付款100已经付款200已经发货300已经收货',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `shipping_method` tinyint(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `invoice_is_needed` tinyint(1) DEFAULT '0',
  `invoice_title` varchar(255) DEFAULT NULL,
  `invoice_message` varchar(255) DEFAULT NULL,
  `consignee_id` int(11) DEFAULT NULL,
  `delivery_at` datetime DEFAULT NULL,
  `pay_at` datetime NOT NULL,
  `refund_at` datetime NOT NULL,
  `please_delivery_at` tinyint(1) NOT NULL DEFAULT '1',
  `memo` varchar(255) DEFAULT NULL COMMENT '注释',
  `express_company_id` smallint(5) unsigned NOT NULL,
  `express_sn` varchar(40) NOT NULL,
  `is_order_product_commented` tinyint(1) NOT NULL DEFAULT '0',
  `consignee_name` varchar(30) DEFAULT NULL,
  `consignee_province` varchar(30) DEFAULT NULL,
  `consignee_city` varchar(30) DEFAULT NULL,
  `consignee_district` varchar(30) DEFAULT NULL,
  `consignee_address` varchar(60) DEFAULT NULL,
  `consignee_zip` char(6) DEFAULT NULL,
  `consignee_mobile` char(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `order_item`
--

CREATE TABLE IF NOT EXISTS `order_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `should_pay_price` decimal(10,2) DEFAULT NULL,
  `actual_pay_price` decimal(10,2) DEFAULT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `order_log`
--

CREATE TABLE IF NOT EXISTS `order_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL COMMENT '订单的id',
  `message` varchar(50) NOT NULL COMMENT '订单处理日志内容',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发生时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='订单处理日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_alipay`
--

CREATE TABLE IF NOT EXISTS `pay_alipay` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(32) NOT NULL COMMENT '帐号',
  `security_code` varchar(32) NOT NULL COMMENT '交易安全校验码',
  `cooperate_user_info` varchar(32) NOT NULL COMMENT '合作者身份ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支付宝支付配置' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_log`
--

CREATE TABLE IF NOT EXISTS `pay_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(32) NOT NULL,
  `result` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支付日志' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pay_method`
--

CREATE TABLE IF NOT EXISTS `pay_method` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `folder` varchar(30) NOT NULL,
  `is_activated` tinyint(1) NOT NULL DEFAULT '0',
  `www` varchar(100) NOT NULL,
  `intro` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支付方式' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `pay_method`
--

INSERT INTO `pay_method` (`id`, `name`, `folder`, `is_activated`, `www`, `intro`) VALUES
(1, '支付宝', '/admin/pay/pay_alipay', 1, 'http://www.alipay.com', '支付宝,你懂的');

-- --------------------------------------------------------

--
-- 表的结构 `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `ad_text` varchar(255) DEFAULT NULL,
  `catalog_id` int(11) DEFAULT NULL,
  `cata_path` varchar(100) NOT NULL,
  `intro` varchar(10000) NOT NULL,
  `price` decimal(10,2) unsigned DEFAULT NULL,
  `market_price` decimal(10,2) unsigned DEFAULT NULL,
  `sold_num` int(10) unsigned NOT NULL DEFAULT '0',
  `rated_num` int(11) unsigned DEFAULT '0',
  `commented_num` int(11) unsigned DEFAULT '0',
  `consulted_num` int(11) unsigned DEFAULT '0',
  `on_sheft_time` datetime DEFAULT NULL,
  `is_on_sheft` tinyint(1) unsigned DEFAULT '0',
  `is_hot` tinyint(1) unsigned DEFAULT '0',
  `is_season` tinyint(1) unsigned DEFAULT '0',
  `is_recommanded` tinyint(1) unsigned DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  `store_num` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `product`
--

INSERT INTO `product` (`id`, `name`, `ad_text`, `catalog_id`, `cata_path`, `intro`, `price`, `market_price`, `sold_num`, `rated_num`, `commented_num`, `consulted_num`, `on_sheft_time`, `is_on_sheft`, `is_hot`, `is_season`, `is_recommanded`, `description`, `store_num`, `create_time`, `is_delete`) VALUES
(1, '123PHPSHOP钻石VIP服务', '23PHPSHOP钻石VIP服务 ', 19, '|19|', '<p style="text-align: center;">&nbsp;<img src="/uploads/image/20150926/1443246384948850.png" title="1443246384948850.png" alt="sercice.png"/></p>', 129999.00, 128888.00, 0, 0, 0, 0, '2015-09-26 06:04:46', 1, 0, 0, 0, NULL, 100, '2015-09-26 04:04:46', 0),
(2, '123PHPSHOP黄金VIP服务', '5×24小时电话+邮件支持服务', 19, '|19|', '<p style="text-align: center;">&nbsp;<img src="/uploads/image/20150926/1443246376112381.png" title="1443246376112381.png" alt="sercice.png"/></p><p><br/></p>', 88888.00, 89999.00, 0, 0, 0, 0, '2015-09-26 06:08:39', 1, 0, 0, 0, NULL, 100, '2015-09-26 04:08:39', 0),
(3, '123PHPSHOP标准VIP服务', '123PHPSHOP标准VIP服务', 19, '|19|', '<p style="text-align: center;"><img src="/uploads/image/20150926/1443246363293993.png" title="1443246363293993.png" alt="sercice.png"/></p>', 49999.00, 48888.00, 0, 0, 0, 0, '2015-09-26 06:09:33', 1, 0, 0, 0, NULL, 100, '2015-09-26 04:09:33', 0),
(4, '123PHPSHOP   VIP服务', '123phpshopVIP服务', 19, '|19|', '<p><img src="/uploads/image/20150926/1443246347444862.png" title="1443246347444862.png" alt="sercice.png"/></p>', 29999.00, 28888.00, 0, 0, 0, 0, '2015-09-26 06:10:11', 1, 0, 0, 0, NULL, 100, '2015-09-26 04:10:11', 0);

-- --------------------------------------------------------

--
-- 表的结构 `product_comment`
--

CREATE TABLE IF NOT EXISTS `product_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `message` varchar(300) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `response_to` int(10) unsigned NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='产品评论' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `product_consult`
--

CREATE TABLE IF NOT EXISTS `product_consult` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `to_question` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `product_id` int(10) unsigned NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_replied` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `product_images`
--

CREATE TABLE IF NOT EXISTS `product_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `image_files` varchar(255) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `uploader_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `shop_info`
--

CREATE TABLE IF NOT EXISTS `shop_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(32) NOT NULL,
  `zip` char(6) NOT NULL,
  `logo_path` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `shop_info`
--

INSERT INTO `shop_info` (`id`, `name`, `email`, `mobile`, `province`, `city`, `district`, `address`, `zip`, `logo_path`) VALUES
(1, '123phpshop', 'service@123phpshop.com', '13391334121', '上海', '上海', '黄浦区', '上海金山工业区亭卫公路6558号5幢', '020000', '/uploads/product/20150926053827_428.png');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `mobile_confirmed` varchar(255) DEFAULT NULL,
  `sms_code` varchar(255) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT '1',
  `birth_date` date DEFAULT NULL,
  `province` varchar(11) DEFAULT NULL,
  `city` varchar(11) DEFAULT NULL,
  `district` varchar(11) DEFAULT NULL,
  `address` varchar(32) DEFAULT NULL,
  `register_at` varchar(255) NOT NULL,
  `last_login_at` varchar(255) NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_consignee`
--

CREATE TABLE IF NOT EXISTS `user_consignee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(32) NOT NULL COMMENT '收货人姓名',
  `mobile` varchar(11) NOT NULL COMMENT '收货人手机号码',
  `province` varchar(10) NOT NULL COMMENT '省份',
  `city` varchar(15) NOT NULL COMMENT '城市',
  `district` varchar(15) NOT NULL COMMENT '地区',
  `address` varchar(100) NOT NULL COMMENT '具体地址',
  `zip` varchar(6) NOT NULL COMMENT '邮政编码',
  `user_id` int(11) unsigned NOT NULL COMMENT '创建人id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已经被删除',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='收货人表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_favorite`
--

CREATE TABLE IF NOT EXISTS `user_favorite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `product_id` int(10) unsigned NOT NULL COMMENT '产品id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收藏表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_view_history`
--

CREATE TABLE IF NOT EXISTS `user_view_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `product_id` int(10) unsigned NOT NULL COMMENT '产品id',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户浏览记录' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
