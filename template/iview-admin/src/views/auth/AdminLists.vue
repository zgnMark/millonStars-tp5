<template>
          <Row :gutter="10">
            <Col span="24">
                <Card>
                  <p slot="title">
                        <Icon type="pinpoint"></Icon>
                        管理员列表
                  </p>
   
                  <Row>
                    <Col span="16">
                     <Form :model="formItem" :label-width="80">
                      <Row justify="center" class="code-row-bg">
                        <Col span="6">
                          <FormItem label="手机号">
                              <Input v-model="formItem.mobile" placeholder="请输入手机号"></Input>
                          </FormItem>
                        </Col>
                        <Col span="6">
                            <FormItem label="邮箱">
                                <Input v-model="formItem.email" placeholder="请输入邮箱"></Input>
                            </FormItem>
                        </Col>
                        <Col span="6">
                          <FormItem label="用户名">
                              <Input v-model="formItem.username" placeholder="请输入用户名"></Input>
                          </FormItem>
                        </Col>
                        <Col span="4">
                          <FormItem label="状态">
                              <Select v-model="formItem.status" placeholder="请选择">
                              <Option value="">请选择</Option>
                                  <Option value="0">正常</Option>
                                  <Option value="1">冻结</Option>
                              </Select>
                          </FormItem>
                        </Col>

                        <Col span="2">
                          <FormItem>
                              <Button type="primary" v-on:click="search">搜索</Button>
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

                    <Modal
                      v-model="groupFlag"
                      title="编辑组"
                      @on-ok="updateGroup"
                      @on-cancel="cancel">
                      <Form ref="roleId" :label-width="80" class="form">
                        <FormItem label="角色" >
                            <CheckboxGroup v-model="groups">
                                <Checkbox  :label="item.id"  v-for="item in groupData" :key="item.id" >
                                  {{item.title}}
                                </Checkbox>
                            </CheckboxGroup>
                        </FormItem>
                      </Form>
                    </Modal>

              </Card>
            </Col>
        </Row>

  
</template>

<script>
 
  import {adminListsAction,adminGroupListAction,saveAdminGroupAction} from '../../api/api'
import {deleteButton,detailsButton} from '../../components/Function'
  export default {
    name: 'admin-lists',
  
    data () {
      return {
                tableTotal: 20,
                current: 1,
                loading:true,
                columns: [
                    {
                        title: '编号',
                        key: 'id',
                        width:100
                    },
                    {
                        title: '用户名',
                        key: 'username'
                        
                    },
                    {
                        title: '邮箱',
                        key: 'email'
                       
                    },
                    {
                        title: '手机',
                        key: 'mobile'
                        
                    },
                    {
                        title: '状态',
                        key: 'status'
                    },
                    {
                        title: '备注',
                        key: 'remark'
                    },
                    
                    {
                        title: '操作',
                        key: 'action',
                        width: 300,
                        align: 'center',
                        render: (h, params) => {
                          return h('div', [
                            detailsButton(
                              ()=>{
                                this.$router.push({ name: 'projectShowDetails', 
                                params: { id: params.row.id }})
                              },h
                            ),
                            h('Button', {
                              props: {
                                type: 'primary',
                                icon:"",
                                size: 'small'
                              },
                              style: {
                                marginRight: '5px'
                              },
                              on: {
                                click: () => {
                                  this.showCreateGroup(params.row.id)
                                }
                              }
                            }, '编辑组'),

                            deleteButton(()=>{this.remove(params.index,params.row.id)},h)
                          ]);
                        }
                    }
                ],
                data: [],
                searchParam:{},
                formItem: {
                    username: '',
                    email:'',
                    mobile:'',
                    status:''
                },
                //编辑角色
                groupFlag:false,
                //所有角色
                groupData:[],
                //选中的角色
                groups:[],
                //当前弹窗选中的用户
                groupId:0

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
                        username:this.searchParam.username,
                        email:this.searchParam.email,
                        mobile:this.searchParam.mobile,
                        status:this.searchParam.status
                    }
                adminListsAction(param).then(response => {
                    this.loading = false
                    this.data =  response.data.list;
                    this.tableTotal = response.data.total;
                    console.log(this.data)
                }).catch(function (error) {
                  console.log(error)
                });
            },
            cancel(){},
            changePage (page) {
                console.log(page)
                this.current = page
                this.data = this.getDataList();
            },
            search (){
                this.searchParam = this.formItem
                this.current = 1
                this.getDataList()
            },
            updateGroup (){
                let param = {
                  id:this.groupId,
                  groups:this.groups.join(",")
                }
                saveAdminGroupAction(param).then(response => {
                  console.log(response)
                  this.$Message.info(response.data.msg);
                }).catch(function (error) {
                  console.log(error)
                });
            },
            showCreateGroup(index) {
                this.groupFlag=true
                this.groupId = index
                let param = {id:index}
                adminGroupListAction(param).then(response => {
                  this.groupData = response.data.list
                  this.groups = response.data.selectData
                }).catch(function (error) {
                  console.log(error)
                });
            },

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
