<template>
        <Row :gutter="10">
            <Col span="24">
             <CheckboxGroup v-model="selectData">
              <Card>
                  <p slot="title">
                        <Icon type="pinpoint"></Icon>
                        规则列表
                  </p>
                  <Row>
                    <Col span="16">
                      <Row   justify="center" align="bottom" style="margin-bottom:15px;" >    
                        <Col span="3">
                            <Button type="primary" shape="circle" class="btn"  @click="ok()">保存</Button>
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
                                {{childItem.title}}
                               </Col>
                               <Col span="20" class="auth-rule-list-right">
                                      <Checkbox :label="childItem.id">{{childItem.title}}</Checkbox>
                               </Col>
                             </Row>
                          </template>
                      </Col>
                    </Row>

                  </Row>
              </Card>
             </CheckboxGroup>
            </Col>
        </Row>

  
</template>

<script>
 
  import {groupRuleListsAction,groupAddAction} from '../../api/api'
  import {deleteButton,detailsButton} from '../../components/Function'
  export default {
    name: 'group-auth',
    data () {
      return {
              data: [],
              selectData:[]
      }
    },
    //加载完成自动执行
    mounted() {
      let id ;
      if(this.$route.params.id <= 0 || this.$route.params.id == undefined) {
         id = localStorage.role_edit_id
      }else{
         localStorage.role_edit_id = this.$route.params.id;
         id = this.$route.params.id
      }
      //获取列表数据
      this.getDataList(id)
    },
    methods: {
            ok () {
                let param = {id:localStorage.role_edit_id,rules:this.selectData}
                groupAddAction(param).then(response => {
                  this.$Message.info(response.data.msg);
                  //更新列表数据
                  this.getDataList(localStorage.role_edit_id)
                }).catch(function (error) {
                  this.$Message.info(response.data.msg);
                });
            },
            //获取数据
            getDataList(id) {
                let param = {id:id}
                groupRuleListsAction(param).then(response => {
                    this.data =  response.data.list;
                    this.selectData = response.data.selectData;
                }).catch(function (error) {
                  console.log(error)
                });
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
.ivu-checkbox-group{
  height: 20px;
}
</style>

