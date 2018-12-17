<template>
          <Row :gutter="10">
            <Col span="24">
                <Card>
                  <p slot="title">
                        <Icon type="pinpoint"></Icon>
                        系统日志列表
                  </p>
   
                  <Row>
                    <Col span="16">
                     <Form :model="formItem" :label-width="80">
                      <Row justify="center" class="code-row-bg">
                        <Col span="6">
                          <FormItem label="日志">
                              <Input v-model="formItem.message" placeholder="请输入日志信息"></Input>
                          </FormItem>
                        </Col>
                       <Col span="4">
                          <FormItem label="应用">
                            <Select v-model="formItem.channel" placeholder="请选择">
                                <Option value="">请选择</Option>
                                <Option value="TP">TP</Option>
                                <Option value="APP">APP</Option>
                                <Option value="LXG_CRONAB">LXG_CRONAB</Option>
                            </Select>
                          </FormItem>
                        </Col>
                        <Col span="4">
                          <FormItem label="级别">
                              <Select v-model="formItem.level" placeholder="请选择">
                              <Option value="">请选择</Option>
                                  <Option value="INFO">INFO</Option>
                                  <Option value="NOTICE">NOTICE</Option>
                                  <Option value="DEBUG">DEBUG</Option>
                                  <Option value="ERROR">ERROR</Option>
                                  <Option value="WARNING">WARNING</Option>
                                  <Option value="ALERT">ALERT</Option>
                                  <Option value="EMERGENCY">EMERGENCY</Option>
                                  <Option value="CRITICAL">CRITICAL</Option>
                              </Select>
                          </FormItem>
                        </Col>
                        <Col span="8">
                          <FormItem label="时间段">
                              <Row>
                                  <Col span="11">
                                      <DatePicker type="datetime" format="yyyy-MM-dd HH:mm" placeholder="开始" v-model="formItem.datemin"></DatePicker>
                                  </Col>
                                  <Col span="2" style="text-align: center">-</Col>
                                  <Col span="11">
                                      <DatePicker type="datetime" format="yyyy-MM-dd HH:mm" placeholder="结束" v-model="formItem.datemax"></DatePicker>
                                  </Col>
                              </Row>
                          </FormItem>
                        </Col>
                        <Col span="2">
                          <FormItem>
                              <Button type="primary" v-on:click="search">搜索日志</Button>
                          </FormItem>
                        </Col>
                      </Row>
                     </Form>
                    </Col>
                  </Row>

                  <Row>
                    <Col span="24" class="main-inner-content">
                      <Table stripe :columns="columns" :data="data"></Table>
                    </Col>
                  </Row>

                  <Row>
                    <Col span="24" class="pages">
                    <Page :total="tableTotal" :page-size="20" :current="1" @on-change="changePage" show-elevator></Page>
                    </Col>
                  </Row>


              </Card>
            </Col>
        </Row>

  
</template>

<script>
 
  import {getSystemLogAction} from '../../api/api'

  export default {
    name: 'systemlog-list',
  
    data () {
      return {
                 tableTotal: 20,
                 current: 1,
                 loading:true,
                 columns: [
                    {
                        title: '编号',
                        key: 'id',
                        width:100,
                        render: (h, params) => {
                          if(params.row.level == 'ERROR'){
                                return h('Font', {
                                  style: {
                                    color: 'red'
                                  }
                                }, params.row.id)
                          }else{
                                return params.row.id;
                          }
                        }
                    },
                    {
                        title: '应用',
                        key: 'channel',
                        width:100,
                        render: (h, params) => {
                          if(params.row.level == 'ERROR'){
                                return h('Font', {
                                  style: {
                                    color: 'red'
                                  }
                                }, params.row.channel)
                          }else{
                                return params.row.channel;
                          }
                        }
                    },
                    {
                        title: '级别',
                        key: 'level',
                        width:100,
                        render: (h, params) => {
                          if(params.row.level == 'ERROR'){
                                return h('Font', {
                                  style: {
                                    color: 'red'
                                  }
                                }, params.row.level)
                          }else{
                                return params.row.level;
                          }
                        }
                    },
                    {
                        title: '时间',
                        key: 'create_time',
                        width:180,
                        render: (h, params) => {
                          if(params.row.level == 'ERROR'){
                                return h('Font', {
                                  style: {
                                    color: 'red'
                                  }
                                }, params.row.create_time)
                          }else{
                                return params.row.create_time;
                          }
                        }
                    },
                    {
                        title: '日志',
                        key: 'message',
                        render: (h, params) => {
                          if(params.row.level == 'ERROR'){
                                return h('Font', {
                                  style: {
                                    color: 'red'
                                  }
                                }, params.row.message)
                          }else{
                                return params.row.message;
                          }
                        }
                    }
                 ],
                data: [],
                searchParam:{},
                formItem: {
                    message: '',
                    channel:'',
                    level:'',
                    datemin:'',
                    datemax:''
                }
        }
    },
    //加载完成自动执行
    mounted() {
      //获取列表数据
      this.getDataList()
    },
    methods: {
            //获取数据
            getDataList() {
                this.loading = true
                let param = {
                        page:this.current,
                        message:this.searchParam.message,
                        channel:this.searchParam.channel,
                        level:this.searchParam.level,
                        datemin:this.searchParam.datemin,
                        datemax:this.searchParam.datemax
                    }
                getSystemLogAction(param).then(response => {
                    this.loading = false
                    this.data =  response.data.list;
                    this.tableTotal = response.data.total;
                    console.log(this.data)
                }).catch(function (error) {
                  console.log(error)
                });
            },
            changePage (page) {
                console.log(page)
                this.current = page
                this.data = this.getDataList();
            },
            search (){
                this.searchParam = this.formItem
                this.current = 1
                this.getDataList()
            }

    }
  }
</script>

<style scope>

  .top {
    margin-top: 15px
  }



  .pages {
    text-align: right;
    margin-top: 10px;
    padding-right: 10px;
  }

  .form {
    margin-right: 20px;
  }

  .be-inline-block {
    /*display: inline-block;*/
    /*line-height: 50px;*/
    vertical-align: middle;
  }
  .button-warp{
   /* margin-bottom: 15px;*/
  }
  .be-inline-block .btn{
    margin-right: 15px;
  }

  .be-inline-block  .searth-input .ivu-input{
    border-radius: 32px;
  }

</style>
