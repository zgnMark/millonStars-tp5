<template>
          <Row :gutter="10">
            <Col span="24">
                <Card>
                  <p slot="title">
                        <Icon type="pinpoint"></Icon>
                        国家列表
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
              :mask-closable="false"
              :title="title"
              @on-ok="ok"
              @on-cancel="cancel">
              <Form ref="country" :model="country"  :label-width="80" class="form">
                <Form-item label="名称" prop="name">
                  <Input v-model="country.name" placeholder="请输入名称"></Input>
                </Form-item>
                <Form-item label="国旗" prop="type">
                  <template>
                      <div class="demo-upload-list" v-for="item in uploadList">
                          <template v-if="item.status === 'finished'">
                              <img :src="item.url">
                          </template>
                          <template v-else>
                              <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                          </template>
                      </div>
                      <Upload
                          ref="upload"
                          :show-upload-list="false"
                          :default-file-list="defaultList"
                          :on-success="handleSuccess"
                          :format="['jpg','jpeg','png']"
                          :max-size="2048"
                          :on-format-error="handleFormatError"
                          :on-exceeded-size="handleMaxSize"
                          :before-upload="handleBeforeUpload"
                          type="drag"
                          :action="uploadApiAction"
                          style="display: inline-block;width:58px;">
                          <div style="width: 58px;height:58px;line-height: 58px;">
                              <Icon type="camera" size="20"></Icon>
                          </div>
                      </Upload>
                  </template>
                </Form-item>
                <Form-item label="中文名称" prop="chinese_name">
                  <Input v-model="country.chinese_name" placeholder="请输入中文名称"></Input>
                </Form-item>
                <Form-item label="英文名称" prop="english_name">
                  <Input v-model="country.english_name" placeholder="请输入英文名称"></Input>
                </Form-item>
                <Form-item label="手机区号" prop="area_code">
                  <Input v-model="country.area_code" placeholder="请输入手机区号"></Input>
                </Form-item>
                <Form-item label="状态" prop="type">
                  <Select v-model="country.status">
                      <Option :value="0">开放</Option>
                      <Option :value="1">关闭</Option>
                  </Select>
                </Form-item>
              </Form>
            </Modal>
        </Row>

  
</template>

<script>
 
  import {uploadApiAction,countryDelAction,countrySaveAction,countryGetAction,countryListAction} from '../../api/api'
  import {base} from '../../api/index'
  import {deleteButton,detailsButton,editButton} from '../../components/Function'

  export default {
    name: 'country-list',
    data () {
      return {
                 tableTotal: 20,
                 current: 1,
                 loading:true,
                 modal:false,
                 title:'新增',
                 columns: [
                    {
                        title: '编号',
                        key: 'id',
                        width:100
                    },
                    {
                        title: '名称',
                        key: 'name'
                    },
                    {
                        title: '中文名称',
                        key: 'chinese_name'
                    },
                    {
                        title: '英文名称',
                        key: 'english_name'
                    },
                    {
                        title: '手机区号',
                        key: 'area_code'
                    },
                    {
                        title: '图片',
                        key: 'logo',
                        render: (h, params) => {
                          if(params.row.logo != ''){
                                return h('Img', {
                                  attrs:{
                                    'src':base + params.row.logo
                                  },
                                  style: {
                                    width: '100px',
                                    'margin-top':'5px',
                                    height:'100px'
                                  }
                                })
                          }else{
                               return 'null'
                          }
                        }
                    },
                    {
                        title: '状态',
                        key: 'status',
                        render: (h, params) => {
                          if(params.row.status == 0){
                                return '开放'
                          }else{
                               return h('Font', {
                                  style: {
                                    color: 'red'
                                  }
                                }, '关闭')
                          }
                        }
                    },
                    {
                        title: '时间',
                        key: 'create_time',
                        width:180
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
                                 this.showCreate(params.row.id)
                              },h
                            ),
                            deleteButton(()=>{this.remove(params.index,params.row.id)},h)
                          ]);
                        }
                    }
                 ],
                 data: [],
                 country:{
                            id:0,
                            name:'',
                            chinese_name:'',
                            english_name:'',
                            area_code:'',
                            logo:'',
                            status:0
                 },

                 //上传
                 uploadApiAction:uploadApiAction,
                 defaultList: [],
                 imgName: '',
                 uploadList: []
      }
    },
    //加载完成自动执行
    mounted() {
      //获取列表数据
      this.getDataList()
      this.uploadList = this.$refs.upload.fileList;
    },
    methods: {
            ok () {
                let param = this.country
                countrySaveAction(param).then(response => {
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
                this.loading = true
                let param = {
                        page:this.current
                    }
                countryListAction(param).then(response => {
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
                  this.title = "更改"
                  let param = {id:index}
                  countryGetAction(param).then(response => {
                    this.country = Object.assign({},this.country,{
                        id:response.data.info.id,
                        name:response.data.info.name,
                        chinese_name:response.data.info.chinese_name,
                        english_name:response.data.info.english_name,
                        area_code:response.data.info.area_code,
                        logo:response.data.info.logo,
                        status:response.data.info.status
                    })
                    if(response.data.info.logo != ''){
                        this.defaultList = [];
                        this.defaultList = this.defaultList.concat(
                                          [
                                              {
                                                  'name': '图片',
                                                  'url': base + response.data.info.logo,
                                                  'status':'finished'
                                              }
                                          ]
                        );
                        this.uploadList  = [Object.assign({},this.defaultList[0])]
                    }else{
                        this.uploadList  = []
                    }
                  }).catch(function (error) {
                    console.log(error)
                  });
              }else{
                this.title = "新增"
                this.country = {}
              }
            },
            remove (index,id) {
                let param = {id:id}
                countryDelAction(param).then(response => {
                  this.$Message.info(response.data.msg);
                  this.data.splice(index, 1);
                }).catch(function (error) {
                  this.$Message.info('网络异常，请稍候再试');
                });
            },
            //上传
            //上传成功
            handleSuccess (res, file) {
                file.url = base + res.data.info;
                file.name = "图片";
                this.country.logo = res.data.info
                this.uploadList  = [Object.assign({},file)]
            },
            //格式
            handleFormatError (file) {
                this.$Notice.warning({
                    title: '格式',
                    desc: '目前支持jpg、jpeg、png格式图片'
                });
            },
            //大小
            handleMaxSize (file) {
                this.$Notice.warning({
                    title: '超出大小',
                    desc: '最大为2M.'
                });
            },
            //上传前
            handleBeforeUpload () {
                
                const check = this.uploadList.length < 2;
                if (!check) {
                    this.$Notice.warning({
                        title: '最多只能上传一张.'
                    });
                }
                return check;
            }
    }


    
  }
</script>
<style>
  .pages {
    text-align: right;
    margin-top: 10px;
    padding-right: 10px;
  }

.demo-upload-list{
    display: inline-block;
    width: 60px;
    height: 60px;
    text-align: center;
    line-height: 60px;
    border: 1px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0,0,0,.2);
    margin-right: 4px;
}
.demo-upload-list img{
    width: 100%;
    height: 100%;
}

</style>