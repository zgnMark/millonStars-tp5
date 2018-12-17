<template>
  <Menu mode="horizontal" theme="dark" active-name="1" class='my-header'>
    <Row>
      <Col span="3">
      <div class="layout-logo"></div>
      </Col>
      <Col span="21">
      <div class="layout-nav">
        <div class="demo-avatar">
          <span>欢迎你！ ClownFish</span>
          <span><a href="#">修改密码</a></span>
          <span><a href="#">退出</a></span>
        </div>
      </div>
      </Col>
    </Row>
  </Menu>
</template>

<script>
  export default {
    name: 'header-view'
  }
</script>

<style>

  .layout-logo {
    width: 100px;
    height: 30px;
    background: #5b6270;
    border-radius: 3px;
    float: left;
    position: relative;
    top: 5px;
    left: 20px;
  }

  .layout-nav {
    margin: 0 auto;
    width: auto;
  }
  .demo-avatar{
    color: #eee;
    float: right;
    margin-right: 20px;
  }
  .demo-avatar a{
    color: #eee;
  }
  .layout-wrap .my-header{
    height:40px;
    line-height:40px
  }
</style>
