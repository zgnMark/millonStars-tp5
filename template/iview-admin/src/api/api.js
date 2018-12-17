/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
import {api,base} from './index';
import Cookies from 'js-cookie';
import qs from 'qs';
let commonParam = {
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded',
    'X-Auth-Token' : localStorage.getItem('token'),
  },
};


//延迟执行
export const delayAction = () => {
	 commonParam = {
	  headers: {
	    'Content-Type': 'application/x-www-form-urlencoded',
	    'X-Auth-Token' : localStorage.getItem('token'),
	  },
	};
}

//系统
export const uploadApiAction = base + '/v1.0.0/backstage/publicity/upload'
                                
//系统
export const getSystemLogAction = (params) => {
   return api.post('/v1.0.0/backstage/getSystemLog',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};//系统日志列表


//后台用户管理
export const adminListsAction = (params) => {
   return api.post('/v1.0.0/backstage/admin/lists',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
export const adminGroupListAction = (params) => {
   return api.post('/v1.0.0/backstage/admin/adminGroupList',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
export const saveAdminGroupAction = (params) => {
   return api.post('/v1.0.0/backstage/admin/saveAdminGroup',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
//管理组列表
export const groupListsAction = (params) => {
   return api.post('/v1.0.0/backstage/group/lists',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
//获取一条管理组
export const groupGetAction = (params) => {
   return api.post('/v1.0.0/backstage/group/get',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
//获取一条管理组
export const groupAddAction = (params) => {
   return api.post('/v1.0.0/backstage/group/add',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
//获取一条管理组
export const groupDelAction = (params) => {
   return api.post('/v1.0.0/backstage/group/del',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
//获取一条管理组
export const groupRuleListsAction = (params) => {
   return api.post('/v1.0.0/backstage/group/ruleLists',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};

//获取一条管理组
export const ruleAddAction = (params) => {
   return api.post('/v1.0.0/backstage/rule/add',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
//获取一条管理组
export const ruleDelAction = (params) => {
   return api.post('/v1.0.0/backstage/rule/del',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
//获取一条管理组
export const ruleGetAction = (params) => {
   return api.post('/v1.0.0/backstage/rule/get',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
//获取一条管理组
export const ruleListsAction = (params) => {
   return api.post('/v1.0.0/backstage/rule/lists',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};

//获取当前拥有的权限
export const userRuleListsAction = (params) => {
   return api.post('/v1.0.0/backstage/rule/accessLists',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};


//保存国家
export const countrySaveAction = (params) => {
   return api.post('/v1.0.0/backstage/country/save',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
export const countryGetAction = (params) => {
   return api.post('/v1.0.0/backstage/country/get',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
export const countryListAction = (params) => {
   return api.post('/v1.0.0/backstage/country/lists',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
export const countryDelAction = (params) => {
   return api.post('/v1.0.0/backstage/country/del',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};

//用户登录
export const publicityLoginAction = (params) => {
   return api.post('/v1.0.0/backstage/publicity/login',qs.stringify(params),commonParam).then(res => (res?res.data:res))
};
   
