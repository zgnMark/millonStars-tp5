<template>

   <Modal
        :mask-closable="false"
        width="800"
        :closable="false"
        v-model="flag"
        @on-ok="ok"
        @on-cancel="cancel"
        cancelText="关闭信息框"
        class-name="vertical-center-modal">
        <Progress :percent="percent" status="active"></Progress>
        <div class="back-bg" id="back-bg">
          <p v-for="item in percentData">{{item.param}}</p>
        </div>
    </Modal>

</template>

<script>

  import {appListAction,envListAction,projectListAction,projectGetAction,projectDelAction,projectUpdateAction,projectNumAction,projectContentAction} from '../api/api'

    //创建项目进度
    export default {
            name:'create-progress',
            props: [
              'flag',
              'uniqueId',
              'progressNum',//进程数
              'progressContent'//进程数
            ],
            data () {
                return {
                    percent:0,
                    percentData:[],
                    progressInterval:null,
                    contentInterval:null,
                }
            },
            //创建组件对外提供的事件
            created: function(){
                let _this = this
                console.log(_this.uniqueId)
                //获取进程信息
                this.$on('get-progress', function(){

                    //获取进度
                    this.progressInterval = setInterval(() => {
                          let param = new FormData();
                          param.append("uniqueId", _this.uniqueId)
                          param.append("progressNum", _this.progressNum)
                          projectNumAction(param).then(response => {
                            console.log(response.data.list)
                            if(response.data.info != 0){
                               _this.percent = response.data.info
                            }
                            if(_this.percent == 100){
                                clearInterval(_this.progressInterval)
                                clearInterval(_this.contentInterval)
                            }
                          }).catch(function (error) {
                            console.log(error)
                          });

                    }, 1000)
                    //获取进度内容
                    this.contentInterval = setInterval(() => {
                         //this.$set('main',this.percentData.concat(response.data.list));
                          let param = new FormData();
                          param.append("uniqueId", _this.uniqueId)
                          param.append("progressContent", _this.progressContent)
                          projectContentAction(param).then(response => {

                            if(response.data.list != null){
                               _this.percentData = _this.percentData.concat(response.data.list);
                            }
                           
                          }).catch(function (error) {
                            console.log(error)
                          });

                         document.getElementById("back-bg").scrollTop = document.getElementById("back-bg").scrollHeight + 500;
                    }, 1000)


                });
                //对外提供获取进度情况
                this.$on('get-progress-num', function(){
                  return _this.percent
                });
            },
            //创建
            mounted () {
                 // this.getProgress()
            },
            //组件销毁
            beforeDestroy () {
              clearInterval(this.progressInterval)
              clearInterval(this.contentInterval)
            },
            methods: {
                ok () {
                   clearInterval(this.progressInterval)
                   clearInterval(this.contentInterval)
                   //跳到最后一步
                   //触发父组件的方法
                   if(this.percent == 100){
                    // this.$emit('finishProgress')
                   }
                },
                cancel(){
                   clearInterval(this.progressInterval)
                   clearInterval(this.contentInterval)
                }
            }
    }
</script>
 <style>

  .back-bg{
     min-height:400px;
     max-height:400px;
     background: #000;
     margin-top:20px;
     color: #FFF;
     overflow: hidden;
     padding:0 5px;
  }

 </style>
