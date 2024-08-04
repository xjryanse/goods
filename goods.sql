/*
 Navicat Premium Data Transfer

 Source Server         : 谢-华为tenancy
 Source Server Type    : MySQL
 Source Server Version : 80032
 Source Host           : 120.46.172.212:3399
 Source Schema         : tenancy

 Target Server Type    : MySQL
 Target Server Version : 80032
 File Encoding         : 65001

 Date: 27/10/2023 15:49:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for w_goods_user_collect
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_user_collect`;
CREATE TABLE `w_goods_user_collect`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `user_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '用户id',
  `spu_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '店铺id',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_id_2`(`user_id`, `spu_id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `spu_id`(`spu_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '用户收藏' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_type_prize_key
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_type_prize_key`;
CREATE TABLE `w_goods_type_prize_key`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `sale_type` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '客户',
  `prize_key` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT 'goods_spu表'
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '商品类型' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_type
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_type`;
CREATE TABLE `w_goods_type`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `sale_type` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '客户',
  `goods_cate` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商品分类\r\nsingle：一单一商品;\r\nfixed：固定商品.\r\nnormal：常规商品',
  `goods_desc` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT 'goods_spu表',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `sale_type`(`sale_type`) USING BTREE,
  UNIQUE INDEX `sale_type_2`(`sale_type`) USING BTREE,
  UNIQUE INDEX `sale_type_3`(`sale_type`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '商品类型' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_trade_mark
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_trade_mark`;
CREATE TABLE `w_goods_trade_mark`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `customer_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '客户',
  `val` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '和analysis_status字段联用',
  `tm_name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商标名称',
  `tmNameSearch` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商标名称搜索用',
  `tm_name_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商标名称(英文)',
  `tm_cate_id` char(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商标分类id',
  `tm_no` char(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商标号',
  `tm_type` char(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商标类型：中文，英文，图形',
  `tm_code` int(0) NOT NULL AUTO_INCREMENT COMMENT '商标编号（系统自增编）',
  `owner_type` char(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '持有人类型：公司；个人',
  `owner_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '卖家id',
  `holder_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '持有人id',
  `holder_customer_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '【冗】持有人',
  `holder_customer_address` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '持有人地址',
  `owner_info` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '持有人信息json串',
  `tm_date` datetime(0) NULL DEFAULT NULL COMMENT '申请日期',
  `tm_pub_date` datetime(0) NULL DEFAULT NULL COMMENT '[外]商标注册日期，专用期(起)',
  `tm_pub_date_end` datetime(0) NULL DEFAULT NULL COMMENT '[外]专用期(止)',
  `source` char(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '发布来源：admin后台；wePub微信公众号；weApp微信小程序',
  `tm_pic` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商标图片',
  `tm_cert_pic` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商标证图片',
  `tm_trust_pic` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商标代理委托书',
  `tm_applier` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '申请人',
  `tm_province` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '注册地：省',
  `tm_city` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '注册地：市',
  `tm_county` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '注册地：县',
  `tm_address` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '地址',
  `tm_agent` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '代理机构',
  `tm_state` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '[外]商标状态',
  `tm_content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '核定使用商品/服务项目',
  `tm_content_code` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '核定编号',
  `tm_pre_pub_no` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '[外]初审公告期号',
  `tm_pre_pub_date` datetime(0) NULL DEFAULT NULL COMMENT '[外]初审公告日期',
  `tm_real_pub_no` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '[外]注册公告期号',
  `tm_real_pub_date` datetime(0) NULL DEFAULT NULL COMMENT '[外]注册公告日期',
  `tm_global_pub_date` datetime(0) NULL DEFAULT NULL COMMENT '[外]国际注册日期',
  `tm_after_date` datetime(0) NULL DEFAULT NULL COMMENT '[外]后期指定日期',
  `tm_pre_auth_date` datetime(0) NULL DEFAULT NULL COMMENT '[外]优先权日期',
  `tm_last_pub` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '[外]最后公告',
  `tm_last_flow` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '[外]最后流程',
  `start_time` datetime(0) NULL DEFAULT NULL COMMENT '有效期起',
  `end_time` datetime(0) NULL DEFAULT NULL COMMENT '有效期止',
  `contain_noary` int(0) NULL DEFAULT 1 COMMENT '包公证？0否1是，默认包',
  `contain_transfer` int(0) NULL DEFAULT 0 COMMENT '包转让？0否1是，默认不包',
  `join_days` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '0' COMMENT '入驻记录：天',
  `audit_status` tinyint(1) NULL DEFAULT 0 COMMENT '审核状态：0未审核、1通过、2拒绝',
  `busier_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '业务员id',
  `goods_status` char(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'offsale' COMMENT '商品状态：\r\noffsale下架\r\nonsale上架\r\nauthorize:授权中\r\nbuying:购买中\r\nrenting:租赁中\r\ntransferd:已过户(相当于失效)',
  `analysis_status` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '记录解析状态(用于导入)：todo，doing，finish。和val字段联用',
  `is_online` int(0) NULL DEFAULT 0 COMMENT '是否线上：0否1是',
  `is_offline` int(0) NULL DEFAULT 1 COMMENT '是否线下：0否1是',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `info_status` tinyint(1) NULL DEFAULT 0 COMMENT '信息状态：0未判断；1正常；2异常',
  `info_msg` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '异常原因',
  `more_cates` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '多类商标',
  `brand_definition` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '品牌释义',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` varchar(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` varchar(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  `tm_rent_monthes` int(0) NULL DEFAULT NULL COMMENT '租期/月',
  `tm_rent_plate` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '租用平台',
  `sellerTmAuthDeposit` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `plateTmAuthDeposit` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `sellerTmAuthAuthorize` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `plateTmAuthAuthorize` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `sellerTmAuthSale` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `plateTmAuthSale` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `sellerTmRentPrize` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `plateTmRentPrize` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `sellerTmBuyDeposit` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `plateTmBuyDeposit` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `sellerTmBuyPrize` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `plateTmBuyPrize` decimal(10, 2) NULL DEFAULT NULL COMMENT '冗余',
  `prizeMd5` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '价格md5(便于取出同类价格的商标)',
  `isTmAuthOn` int(0) NULL DEFAULT 0 COMMENT '冗余',
  `isTmRentOn` int(0) NULL DEFAULT 0 COMMENT '冗余',
  `isTmBuyOn` int(0) NULL DEFAULT 0 COMMENT '冗余',
  `goodsTmAuthId` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商标授权商品id',
  `goodsTmRentId` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商标租用商品id',
  `goodsTmBuyId` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商标购买商品id',
  `TmAuthSale` decimal(10, 2) GENERATED ALWAYS AS ((`sellerTmAuthSale` + `plateTmAuthSale`)) VIRTUAL COMMENT '【客户】商标授权售价' NULL,
  `TmRentPrize` decimal(10, 2) GENERATED ALWAYS AS ((`sellerTmRentPrize` + `plateTmRentPrize`)) VIRTUAL COMMENT '【客户】商标租用价' NULL,
  `TmBuyPrize` decimal(10, 2) GENERATED ALWAYS AS ((`sellerTmBuyPrize` + `plateTmBuyPrize`)) VIRTUAL NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tm_code`(`tm_code`) USING BTREE,
  UNIQUE INDEX `tm_no`(`tm_no`, `customer_id`, `tm_cate_id`) USING BTREE COMMENT '同供应商，同分类，同商标号唯一',
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `tm_cate_id`(`tm_cate_id`) USING BTREE,
  INDEX `owner_id`(`owner_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE,
  INDEX `owner_type`(`owner_type`) USING BTREE,
  INDEX `audit_status`(`audit_status`) USING BTREE,
  INDEX `holder_id`(`holder_id`) USING BTREE,
  INDEX `busier_id`(`busier_id`) USING BTREE,
  INDEX `val`(`val`(8)) USING BTREE,
  INDEX `has_used`(`has_used`) USING BTREE,
  INDEX `tm_name`(`tm_name`) USING BTREE,
  INDEX `goods_status`(`goods_status`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE,
  INDEX `goods_status_2`(`goods_status`, `status`, `is_delete`, `company_id`, `busier_id`, `creater`) USING BTREE,
  INDEX `info_status`(`info_status`) USING BTREE,
  INDEX `is_online`(`is_online`) USING BTREE,
  INDEX `source`(`source`) USING BTREE,
  INDEX `is_offline`(`is_offline`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '商标信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_spu
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_spu`;
CREATE TABLE `w_goods_spu`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `cate_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商品分类：选择属性使用',
  `group_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT 'w_goods_group表；前端展示的分组',
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商品名称',
  `goods_desc` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '商品描述',
  `single_sku` tinyint(1) NULL DEFAULT 0 COMMENT '单sku模式',
  `main_pic` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商品主图',
  `sub_pics` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '商品子图',
  `sale_type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'normal' COMMENT 'sale_type',
  `line_prize` decimal(10, 2) NULL DEFAULT NULL,
  `min_prize` decimal(10, 2) NULL DEFAULT NULL COMMENT 'spu最低价',
  `max_prize` decimal(10, 2) NULL DEFAULT NULL COMMENT 'spu最高价',
  `unit` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '单位',
  `tips` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '标签',
  `active` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '促销活动',
  `service` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '服务描述',
  `detail` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '商品详情',
  `sort` int(0) NULL DEFAULT 1000,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE,
  INDEX `cate_id`(`cate_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '商品spu表，sku为goods表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_prize_tpl
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_prize_tpl`;
CREATE TABLE `w_goods_prize_tpl`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` int(0) NULL DEFAULT 0,
  `p_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '父费用key',
  `prize_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '费用key',
  `prize_name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '费用名称',
  `main_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '主费用key',
  `main_name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '主名',
  `sale_type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '销售类型',
  `belong_role` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '归属角色',
  `default_money` double(10, 2) NULL DEFAULT 0.00 COMMENT '默认金额',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE,
  INDEX `prize_key`(`prize_key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '价格模板设置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_prize_ref_tpl
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_prize_ref_tpl`;
CREATE TABLE `w_goods_prize_ref_tpl`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `cancel_by` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '取消人',
  `flow_node_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '流程点',
  `grade` int(0) NULL DEFAULT 1 COMMENT '优先级：数字越大，越优先判断，同一值的，同时判断',
  `belong_role` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '退款角色：客户buyer，供应商seller',
  `ref_to_role` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '退款给谁：客户buyer，供应商seller',
  `change_type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '1应收(供应商退)，2应付(退客户)',
  `ref_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '退款key：tmAuthSellerRef',
  `prize_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '关联价格key',
  `rate` decimal(10, 2) NULL DEFAULT NULL COMMENT '收比率：0.6表示收60%，退30%',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE,
  INDEX `prize_key`(`prize_key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '退款规则设置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_prize_key_group
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_prize_key_group`;
CREATE TABLE `w_goods_prize_key_group`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `sale_type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'pay' COMMENT '销售类型',
  `group_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '分组key:',
  `prize_type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '费用类型：buyer,seller,other',
  `prize_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '费用key',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE,
  INDEX `sale_type`(`sale_type`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '销售类型可能出现的全部价格key' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_prize_key_calc
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_prize_key_calc`;
CREATE TABLE `w_goods_prize_key_calc`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` int(0) NULL DEFAULT NULL,
  `prize_key_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '价格key',
  `describe` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '描述',
  `min_val` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '分段起',
  `min_contain` tinyint(1) NULL DEFAULT 0 COMMENT '最小包含？> ? >=',
  `max_val` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '分段止',
  `max_contain` tinyint(1) NULL DEFAULT 1 COMMENT '最大包含？< ? <=',
  `math_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '价格key，system_math表',
  `fixed_data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '参数(带入计算)；json格式',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '价格key计算公式' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_prize_key
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_prize_key`;
CREATE TABLE `w_goods_prize_key`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `key_group` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'goods' COMMENT '费用群组：goods归属商品；order归属订单',
  `type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'pay' COMMENT '费用类型：pay:付款；ref:退款',
  `change_type` tinyint(1) NULL DEFAULT 0 COMMENT '1应收，2应付',
  `p_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `prize_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '费用key',
  `prize_name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '费用名称',
  `rely_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '依赖费用（只有依赖费用有值，他才能有值）',
  `from_role` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '归属角色',
  `to_role` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '归属角色',
  `scope_field` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '分段字段：prize_key_calc表',
  `is_duplicate` tinyint(1) NULL DEFAULT 0 COMMENT '是否支持多笔，默认0否，1是',
  `to_money` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '到账方式：空:手工；money:到余额；sec_share:分账(微信)',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `prize_key`(`prize_key`, `company_id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '价格key设置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_prize_group
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_prize_group`;
CREATE TABLE `w_goods_prize_group`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `group_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'pay' COMMENT '分组key',
  `group_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '0' COMMENT '分组名',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `group_key`(`group_key`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '价格分组' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_prize
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_prize`;
CREATE TABLE `w_goods_prize`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `pid` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '归属价格',
  `goods_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商品id',
  `prize_type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '费用类型:次',
  `prize_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '费用key',
  `prize_name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '费用名称',
  `belong_role` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '归属角色',
  `belong_user_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '归属角色',
  `prize` decimal(10, 2) UNSIGNED NULL DEFAULT NULL COMMENT '报价',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `goods_id_2`(`goods_id`, `prize_key`) USING BTREE COMMENT '防止相同商品相同价格key重复写入造成价格异常',
  INDEX `pid`(`pid`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '商品售价表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_group
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_group`;
CREATE TABLE `w_goods_group`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `company_id` int(0) NULL DEFAULT 0,
  `group` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `group_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '分组名',
  `group_pic` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '分组图，商城展示',
  `sort` int(0) NULL DEFAULT 1000,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `group`(`group`, `group_name`, `company_id`) USING BTREE,
  INDEX `group_name`(`group_name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '商品分组表，同一组放在一起展示' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_cate
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_cate`;
CREATE TABLE `w_goods_cate`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `company_id` int(0) NULL DEFAULT 0,
  `pid` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '父级分类',
  `group` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'normal' COMMENT '商品分组：mall',
  `cate` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '分类',
  `cate_pic` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '分类图，商城展示',
  `sort` int(0) NULL DEFAULT 1000,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE,
  INDEX `group`(`group`) USING BTREE,
  INDEX `status`(`status`) USING BTREE,
  INDEX `is_delete`(`is_delete`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '分类总表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_brand
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_brand`;
CREATE TABLE `w_goods_brand`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` int(0) NULL DEFAULT NULL COMMENT '端口',
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '名称',
  `first_letter` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '首字母',
  `factory_status` int(0) NULL DEFAULT NULL COMMENT '是否为品牌制造商：0->不是；1->是',
  `logo` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '品牌logo',
  `big_pic` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '专区大图',
  `brand_story` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '品牌故事',
  `product_count` int(0) NULL DEFAULT NULL COMMENT '产品数量',
  `product_comment_count` int(0) NULL DEFAULT NULL COMMENT '产品评论数量',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '商品品牌' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_attr_value
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_attr_value`;
CREATE TABLE `w_goods_attr_value`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `key_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商品属性key的id',
  `attr_value` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '属性值',
  `sort` int(0) NULL DEFAULT 1000,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE,
  INDEX `key_id`(`key_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '分类总表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_attr_key
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_attr_key`;
CREATE TABLE `w_goods_attr_key`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `company_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '',
  `cate_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商品分类id',
  `attr_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '属性名称',
  `sort` int(0) NULL DEFAULT 1000,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '创建者',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cate_id`(`cate_id`, `attr_name`) USING BTREE COMMENT '每个分类每个属性名唯一',
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `update_time`(`update_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '分类总表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `w_goods_attr`;
CREATE TABLE `w_goods_attr`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` int(0) NULL DEFAULT NULL COMMENT '端口',
  `goods_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '客户',
  `attr_name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT 'attr_key表\r\n',
  `attr_value` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT 'attr_value表',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `goods_table`(`attr_value`) USING BTREE,
  INDEX `goods_id`(`goods_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '商品表，即SKU' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for w_goods
-- ----------------------------
DROP TABLE IF EXISTS `w_goods`;
CREATE TABLE `w_goods`  (
  `id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `company_id` int(0) NULL DEFAULT NULL COMMENT '端口',
  `customer_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '客户',
  `spu_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT 'goods_spu表',
  `cate_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商品分类id',
  `goods_table` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商品详情表',
  `goods_table_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '商品详情表id',
  `goods_pic` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商品主图',
  `sub_pics` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '商品子图',
  `goods_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '商品名称',
  `goods_desc` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '商品描述',
  `goods_value` decimal(10, 2) NULL DEFAULT NULL,
  `line_prize` decimal(10, 2) NULL DEFAULT NULL COMMENT '划线价',
  `sellerGoodsPrize` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '供应商商品一口价',
  `plateGoodsPrize` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '平台一口价加价',
  `goodsPrize` decimal(10, 2) GENERATED ALWAYS AS (((`sellerGoodsPrize` * 1) + (`plateGoodsPrize` * 1))) VIRTUAL NULL,
  `shop_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '归属店铺',
  `sale_type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '销售类型：商标授权、商标租用、购买商标、购买网店',
  `is_on` int(0) NULL DEFAULT 1 COMMENT '是否上架：0否，1是',
  `stock` int(0) NULL DEFAULT 0 COMMENT '库存量',
  `unit` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '[冗]同spu一样',
  `audit_status` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '1' COMMENT '审核状态：待审核，已同意，已拒绝',
  `goods_status` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'onsale' COMMENT '商品状态：\r\noffsale下架\r\nonsale上架\r\nauthorize:授权中\r\nbuying:购买中\r\nrenting:租赁中\r\ntransferd:已过户(相当于失效)',
  `audit_user_id` char(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '审核用户',
  `audit_describe` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '审核意见',
  `scan_times` int(0) NULL DEFAULT 0 COMMENT '浏览人次',
  `scan_users` int(0) NULL DEFAULT NULL COMMENT '浏览人数',
  `seller_user_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '卖家',
  `busier_id` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '业务员',
  `sort` int(0) NULL DEFAULT 1000 COMMENT '排序',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态(0禁用,1启用)',
  `has_used` tinyint(1) NULL DEFAULT 0 COMMENT '有使用(0否,1是)',
  `is_lock` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未锁，1：已锁）',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '锁定（0：未删，1：已删）',
  `remark` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL COMMENT '备注',
  `creater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '创建者，user表',
  `updater` char(19) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT '' COMMENT '更新者，user表',
  `create_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `goods_table_2`(`goods_table`, `goods_table_id`, `sale_type`) USING BTREE,
  INDEX `audit_status`(`audit_status`) USING BTREE,
  INDEX `stock`(`stock`) USING BTREE,
  INDEX `sale_type`(`sale_type`) USING BTREE,
  INDEX `goods_table`(`goods_table`) USING BTREE,
  INDEX `goods_table_id`(`goods_table_id`) USING BTREE,
  INDEX `shop_id`(`shop_id`) USING BTREE,
  INDEX `is_on`(`is_on`) USING BTREE,
  INDEX `audit_user_id`(`audit_user_id`) USING BTREE,
  INDEX `busier_id`(`busier_id`) USING BTREE,
  INDEX `seller_user_id`(`seller_user_id`) USING BTREE,
  INDEX `company_id`(`company_id`) USING BTREE,
  INDEX `spu_id`(`spu_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '商品表，即SKU' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
