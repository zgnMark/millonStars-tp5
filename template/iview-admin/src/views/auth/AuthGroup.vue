<template>
          <Row :gutter="10">
            <Col span="24">
                <Card>
                  <p slot="title">
                        <Icon type="pinpoint"></Icon>
                        管理组列表
                  </p>
                  <Row>
                    <Col span="16">
                      <Row   justify="center" align="bottom" style="margin-bottom:15px;" >    
                        <Col span="3">
                            <Button type="primary" shape="circle" class="btn" icon="plus-round" @click="showCreate()">新增</Button>
                        </Col>
                      </Row>
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


            <Modal
              v-model="modal"
              :title="title"
              @on-ok="ok"
              @on-cancel="cancel">
              <Form ref="group" :model="group"  :label-width="80" class="form">
                <Form-item label="组名称" prop="business">
                  <Input v-model="group.title" placeholder="请输入角色名称"></Input>
                </Form-item>
                <Form-item label="备注" prop="Remark">
                  <Input v-model="group.remarks" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入备注"></Input>
                </Form-item>
              </Form>
            </Modal>
        </Row>



</template>

<script>
 
  import {groupListsAction,groupGetAction,groupAddAction,groupDelAction} from '../../api/api'
  import {deleteButton,detailsButton,editButton} from '../../components/Function'
  export default {
    name: 'auth-group',
  
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
                      title: '组名',
                      key: 'title'
                      
                  },
                  {
                      title: '备注',
                      key: 'remarks'
                  },
                  {
                    title: '操作',
                    key: 'action',
                    width: 300,
                    align: 'center',
                    render: (h, params) => {
                      return h('div', [
                        editButton(
                          ()=>{
                             this.showCreate(params.row.id);
                          },h
                        ),
                        h('Button', {
                                  props: {
                                    type: 'primary',
                                    icon:"ios-eye-outline",
                                    size: 'small'
                                  },
                                  style: {
                                    marginRight: '5px'
                                  },
                                  on: {
                                    click: () => {
                                      this.$router.push({ name: 'groupAuth',params: { id: params.row.id }})
                                    }
                                  }
                                }, '编辑权限'),
                        deleteButton(()=>{this.remove(params.index,params.row.id)},h)
                      ]);
                    }
                  }
                ],
                data: [],
                modal: false,
                title:'',
                group: {
                    id:'',
                    title:'',
                    remarks:''
                }
        }
    },
    //加载完成自动执行
    mounted() {
      //获取列表数据
      this.getDataList()
    },
    methods: {
            ok () {
                let param = {
                      id:this.group.id,
                      title:this.group.title,
                      remarks:this.group.remarks
                }
                groupAddAction(param).then(response => {
                  this.$Message.info("编辑成功");
                  //更新列表数据
                  this.getDataList()
                }).catch(function (error) {
                  this.$Message.info(response.data.msg);
                });
            },
            cancel(){},
            //获取数据
            getDataList() {
                this.loading = true
                let param = {
                        page:this.current
                    }
                groupListsAction(param).then(response => {
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
            showCreate (index) {
              this.modal=true
              if(index > 0){
                  this.title = "更改组"
                  let param = {id:index}
                  groupGetAction(param).then(response => {
                    this.group = Object.assign({},this.group,{
                        id:response.data.info.id,
                        title:response.data.info.title,
                        remarks:response.data.info.remarks
                    })
                  }).catch(function (error) {
                    console.log(error)
                  });
              }else{
                this.title = "新增组"
                this.group = {
                  id:'',
                  title:'',
                  remarks:''
                }
              }
            },
            remove (index,id) {
                let param = {id:id}
                groupDelAction(param).then(response => {
                  this.$Message.info(response.data.msg);
                  this.data.splice(index, 1);
                }).catch(function (error) {
                  this.$Message.info('删除失败');
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
