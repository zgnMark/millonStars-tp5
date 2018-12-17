<template>
  <div class="bread-crumb">
    <Row >
      <Col>
      <Breadcrumb>
        <Breadcrumb-item>{{home}}</Breadcrumb-item>
        <Breadcrumb-item>{{module}}</Breadcrumb-item>
        <Breadcrumb-item>{{method}}</Breadcrumb-item>
      </Breadcrumb>
      </Col>
    </Row>
    <hr style="height:1px ;border:1px solid transparent;border-top: 1px solid #ddd; margin: 5px -25px">
  </div>

</template>

<script>
    export default {
        name:'bread-crumb',
        props: [
          'home',
          'module',
          'method'
        ],
    }
</script>

<style>
  .bread-crumb {
    padding: 10px
  }

</style>
