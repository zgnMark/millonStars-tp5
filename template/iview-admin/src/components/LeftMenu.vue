<template>
  <Menu active-name="1-1" width="auto"  :open-names="['1']">


    <Submenu name="1">
      <template slot="title"><Icon type="ios-navigate"></Icon>
        应用管理
      </template>
      <Menu-item name="1-1"> <router-link to="/application/list" style="color:#495060;">应用列表</router-link> </Menu-item>
    </Submenu>

    <Submenu name="2">
      <template slot="title"><Icon type="ios-keypad"></Icon>
        发布环境管理
      </template>
      <Menu-item name="2-1"><router-link to="/env/list" style="color:#495060;">环境列表</router-link></Menu-item>
      <Menu-item name="2-2"><router-link to="/env/add" style="color:#495060;">添加环境</router-link></Menu-item>
    </Submenu>

    <Submenu name="3">
      <template slot="title"><Icon type="ios-keypad"></Icon>
        程序包管理
      </template>
      <Menu-item name="3-1"><router-link to="/project/list" style="color:#495060;">程序包列表</router-link></Menu-item>
      <Menu-item name="3-2"><router-link to="/project/add" style="color:#495060;">添加程序包</router-link></Menu-item>
    </Submenu>

    <Submenu name="4">
      <template slot="title">
        <Icon type="ios-analytics"></Icon>
        服务器管理
      </template>
      <Menu-item name="4-1">
        <router-link to="/server/list" style="color:#495060;"><Icon type="ios-analytics"></Icon>服务器列表</router-link>
      </Menu-item>
      <Menu-item name="4-2">
        <router-link to="/agent/list" style="color:#495060;"><Icon type="ios-analytics"></Icon>跳板机列表</router-link>
      </Menu-item>
    </Submenu>

    <Submenu name="5">
      <template slot="title">
        <Icon type="ios-analytics"></Icon>
        用户管理
      </template>
      <Menu-item name="5-1">
        <Icon type="ios-analytics"></Icon>
        用户列表
      </Menu-item>
      <Menu-item name="5-2"><Icon type="ios-analytics"></Icon>
        权限管理
      </Menu-item>
    </Submenu>



  </Menu>




</template>

<script>
    export default {
        name:'left-menu'
    }
</script>
 <style>

   .ivu-menu-vertical.ivu-menu-light:after{
     height: 100vh;
   }

 </style>
