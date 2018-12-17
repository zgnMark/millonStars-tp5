<style scoped>
  .layout{
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
  }
  .layout-breadcrumb{
    padding: 10px 15px 0;
  }
  .layout-content{
    min-height: 200px;
    margin: 15px;
    overflow: hidden;
    background: #fff;
    border-radius: 4px;
  }
  .layout-content-main{
    padding: 10px;
  }
  .layout-copy{
    text-align: center;
    padding: 10px 0 20px;
    color: #9ea7b4;
  }
  .layout-menu-left{
    background: #464c5b;
  }
  .layout-header{
    height: 60px;
    background: #fff;
    box-shadow: 0 1px 1px rgba(0,0,0,.1);
  }
  .layout-logo-left{
    width: 90%;
    height: 30px;
    background: #5b6270;
    border-radius: 3px;
    margin: 15px auto;
  }
</style>
<template>
  <div class="layout">
    <Row type="flex">
      <Col span="5" class="layout-menu-left">
      <Menu active-name="1-2" theme="dark" width="auto" :open-names="['1']">
        <div class="layout-logo-left"></div>
        <Submenu name="1">
          <template slot="title">
            <Icon type="ios-navigate"></Icon>
            导航一
          </template>
          <MenuItem name="1-1">选项 1</MenuItem>
          <MenuItem name="1-2">选项 2</MenuItem>
          <MenuItem name="1-3">选项 3</MenuItem>
        </Submenu>
        <Submenu name="2">
          <template slot="title">
            <Icon type="ios-keypad"></Icon>
            导航二
          </template>
          <MenuItem name="2-1">选项 1</MenuItem>
          <MenuItem name="2-2">选项 2</MenuItem>
        </Submenu>
        <Submenu name="3">
          <template slot="title">
            <Icon type="ios-analytics"></Icon>
            导航三
          </template>
          <MenuItem name="3-1">选项 1</MenuItem>
          <MenuItem name="3-2">选项 2</MenuItem>
        </Submenu>
      </Menu>
      </Col>
      <Col span="19">
      <div class="layout-header"></div>
      <div class="layout-breadcrumb">
        <Breadcrumb>
          <BreadcrumbItem href="#">首页</BreadcrumbItem>
          <BreadcrumbItem href="#">应用中心</BreadcrumbItem>
          <BreadcrumbItem>某应用</BreadcrumbItem>
        </Breadcrumb>
      </div>
      <div class="layout-content">
        <div class="layout-content-main">内容区域</div>
      </div>
      <div class="layout-copy">
        2011-2016 &copy; TalkingData
      </div>
      </Col>
    </Row>
  </div>
</template>
<script>
  export default {

  }
</script>
