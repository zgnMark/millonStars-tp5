<template>
        <Row :gutter="10">
            <Col span="24">
              <Card>
                  <p slot="title">
                        <Icon type="pinpoint"></Icon>
                        规则列表
                  </p>
                  <Row>
                    <Col span="16">
                      <Row   justify="center" align="bottom" style="margin-bottom:15px;" >    
                        <Col span="3">
                            <Button type="primary" shape="circle" class="btn" icon="plus-round" @click="createGroup(0,0)">新增规则组</Button>
                        </Col>
                      </Row>
                    </Col>
                  </Row>
                  <Row class="auth-rule-outside">
                    <Row class="auth-rule-header">
                      <Col span="4" class="auth-rule-group" >
                      规则组名
                      </Col>
                      <Col span="20" class="auth-rule-list">
                      规则列表
                      </Col>
                    </Row>

                    <Row class="auth-rule-child" v-for="item in data" :key="item.id">
                      <Col span="4" class="auth-rule-group">
                       <Row>
                         <Col span="24">
                           <span>{{ item.name }}</span>
                           <br />
                           <Button icon="ios-plus-empty" type="dashed" size="small" @click="showCreate(item.id,0)">添加</Button>
                           <Button icon="edit" type="dashed" size="small" @click="createGroup(item.id,1)">编辑</Button>
                           <Button icon="ios-close-empty" type="dashed" size="small" @click="delRule(item.id)">删除</Button>
                         </Col>
                       </Row>
                      </Col>
                      <Col span="20" class="auth-rule-list">
                          <template v-if="item.childData == ''">
                             <Row class="auth-rule-list-item" >
                               <Col span="4" class="auth-rule-list-left"></Col>
                               <Col span="20" class="auth-rule-list-right"></Col>
                             </Row>
                          </template>
                          <template v-else>
                             <Row v-for="(childItem,key,index) in item.childData" class="auth-rule-list-item"  v-bind:class="{'auth-line':key!=0}" :key="childItem.id">
                               <Col span="4" class="auth-rule-list-left">
                                {{childItem.name}}
                                <br />
                                [{{childItem.title}}]
                               </Col>
                               <Col span="20" class="auth-rule-list-right">
                                 <Button icon="edit" type="dashed" size="small" @click="showCreate(childItem.id,1)">编辑</Button>
                                 <Button icon="ios-close-empty" type="dashed" size="small" @click="delRule(childItem.id)">删除</Button>
                               </Col>
                             </Row>
                          </template>
                      </Col>
                    </Row>

                  </Row>
              </Card>
            </Col>

            <Modal
              v-model="modal"
              :title="title"
              @on-ok="ok"
              @on-cancel="cancel">
              <Form ref="rule" :model="rule"  :label-width="80" class="form">
                <Form-item label="规则名称" prop="name">
                  <Input v-model="rule.name" placeholder="请输入角色名称"></Input>
                </Form-item>
                <Form-item label="规则类型" prop="type">
                  <Select v-model="rule.type">
                      <Option :value="0">规则名称验证</Option>
                      <Option :value="1">正则验证规则</Option>
                  </Select>
                </Form-item>
                <Form-item label="验证规则" >
                  <Input v-model="rule.condition" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入验证规则"></Input>
                </Form-item>
                <Form-item label="备注" prop="title">
                  <Input v-model="rule.title" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="请输入备注"></Input>
                </Form-item>
              </Form>
            </Modal>

        </Row>

  
</template>

<script>
 
  import {ruleAddAction,ruleDelAction,ruleGetAction,ruleListsAction} from '../../api/api'
import {deleteButton,detailsButton} from '../../components/Function'
  export default {
    name: 'admin-lists',
    data () {
      return {
              data: [],
              modal: false,
              title:'',
              rule: {
                  id:'',
                  name:'',
                  title:'',
                  type:'',
                  status:1,
                  condition:"",
                  pid:0
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
                let param = this.rule
                ruleAddAction(param).then(response => {
                  this.$Message.info(response.data.msg);
                  //更新列表数据
                  this.getDataList()
                }).catch(function (error) {
                  this.$Message.info(response.data.msg);
                });
            },
            cancel(){},
            //获取数据
            getDataList() {
                let param = {}
                ruleListsAction(param).then(response => {
                    this.data =  response.data.list;
                    console.log(this.data)
                }).catch(function (error) {
                  console.log(error)
                });
            },
            showCreate (id,index) {
              this.modal=true
              if(index > 0){
                  this.title = "更改"
                  let param = {id:id}
                  ruleGetAction(param).then(response => {
                    this.rule = Object.assign({},this.rule,{
                        id:response.data.info.id,
                        name:response.data.info.name,
                        title:response.data.info.title,
                        type:response.data.info.type,
                        status:response.data.info.status,
                        condition:response.data.info.condition,
                        pid:response.data.info.pid
                    })
                  }).catch(function (error) {
                    console.log(error)
                  });
              }else{
                this.title = "新增"
                this.rule = {
                  id:'',
                  name:'',
                  title:'',
                  type:0,
                  status:1,
                  condition:"",
                  pid:id
                }
              }
            },
            createGroup(id,index) {
                
                if( index > 0 ) {
                    let param = {id:id}
                    ruleGetAction(param).then(response => {
                      this.rule = Object.assign({},this.rule,{
                          id:response.data.info.id,
                          name:response.data.info.name,
                          title:response.data.info.title,
                          type:response.data.info.type,
                          status:response.data.info.status,
                          condition:response.data.info.condition,
                          pid:response.data.info.pid
                      })
                     
                    })
                }else{
                  this.rule = {}
                }

                this.$Modal.confirm({
                    render: (h) => {
                        return h('Input', {
                            props: {
                                value: this.rule.name,
                                autofocus: true,
                                placeholder: '请输入组名称'
                            },
                            on: {
                                input: (val) => {
                                    this.value = val;
                                }
                            }
                        })
                    },
                    onOk:() => {
                      let param = Object.assign({},this.rule,{name:this.value,title:this.value}) 
                      ruleAddAction(param).then(response => {
                        this.$Message.info(response.data.msg);
                        this.getDataList()
                      }).catch(function (error) {
                        console.log(error)
                      });
                    }
                })
            },
            delRule(id){


                this.$Modal.confirm({
                    title:'确认',
                    content:'确定删除这条信息吗？',
                    onOk:() => {
                        let param = {id:id}
                        ruleDelAction(param).then(response => {
                          this.$Message.info(response.data.msg);
                          this.getDataList()
                        }).catch(function (error) {
                          console.log(error)
                        });
                    }
                })

            }
   
    }
  }
</script>

<style scope>
.auth-line{
  border-top: 1px solid #DDD;
}
.auth-rule-outside{
   border: 1px solid #DDD;
}
.auth-rule-header{
  height: 50px;
  line-height: 50px;
}
.auth-rule-group{
  align-items:center;
  text-align: center;
}
.auth-rule-list{
  border-left: 1px solid #DDD;
  align-items:center;
  text-align: center;
  height: 100%;
  padding:0;
}
.auth-rule-list-item{
    display:flex;
  justify-content:center;
  align-items:center;
}
.auth-rule-child{
  border-top: 1px solid #DDD;
  text-align:center;
  display:flex;
  justify-content:center;
  align-items:center;
}
.auth-rule-list-left{
  text-align: center;
  border-right: 1px solid #DDD;
  min-height: 60px;
  display:flex;
  justify-content:center;
  align-items:center;
}
.auth-rule-list-right{
  text-align: center;
  display:flex;
  justify-content:center;
  align-items:center;
}
</style>

