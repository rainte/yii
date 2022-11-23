<?php

/**
 * @apiDefine Request 公共参数
 * @apiParam (RequestParam) {String} method API接口名称.
 * @apiParam (RequestParam) {Number} ver 版本号.
 * @apiParam (RequestParam) {String} timestamp 时间戳[2018-01-01 01:00:00].
 * @apiParam (RequestParam) {String=json,xml} format 响应格式.
 * @apiParam (RequestParam) {String} app_key 应用AppKey.
 * @apiParam (RequestParam) {String} session 应用授权信息.
 * @apiParam (RequestParam) {String=md5} sign_method 签名算法.
 * @apiParam (RequestParam) {String} sign 签名.
 * @apiParam (RequestParam) {String} [token] TOKEN.
 * @apiParam (RequestParam) {String} [json] 业务参数.
 */
/**
 * @apiDefine Response 响应参数
 * @apiSuccess (ResponseParam) {String} code 提示编码.
 * @apiSuccess (ResponseParam) {String} message 提示信息.
 */

/**
 * @apiDefine RequestParam 公共参数
 */
/**
 * @apiDefine ResponseParam 响应参数
 */
/**
 * @apiDefine BusinessParam 请求参数
 */
/**
 * @apiDefine Interface 接口管理
 */
