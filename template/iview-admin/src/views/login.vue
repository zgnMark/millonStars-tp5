<style lang="less">
    @import './login.less';
</style>

<template>
    <div class="login" @keydown.enter="handleSubmit">
        <div class="login-con">
            <Card :bordered="false">
                <p slot="title">
                    <Icon type="log-in"></Icon>
                    欢迎登录
                </p>
                <div class="form-con">
                    <Form ref="loginForm" :model="form" :rules="rules">
                        <FormItem prop="userName">
                            <Input v-model="form.userName" placeholder="请输入用户名">
                                <span slot="prepend">
                                    <Icon :size="16" type="person"></Icon>
                                </span>
                            </Input>
                        </FormItem>
                        <FormItem prop="password">
                            <Input type="password" v-model="form.password" placeholder="请输入密码">
                                <span slot="prepend">
                                    <Icon :size="14" type="locked"></Icon>
                                </span>
                            </Input>
                        </FormItem>
                        <FormItem>
                            <Button @click="handleSubmit" type="primary" long>登录</Button>
                        </FormItem>
                    </Form>
                    <p class="login-tip">输入任意用户名和密码即可</p>
                </div>
            </Card>
        </div>
    </div>
</template>

<script>
import Cookies from 'js-cookie';
import {publicityLoginAction,delayAction} from '../api/api'
export default {
    data () {
        return {
            form: {
                userName: '',
                password: ''
            },
            rules: {
                userName: [
                    { required: true, message: '账号不能为空', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: '密码不能为空', trigger: 'blur' }
                ]
            }
        };
    },
    methods: {
        handleSubmit () {
            this.$refs.loginForm.validate((valid) => {
                if (valid) {
                        let param = {
                            username : this.form.userName,
                            password : this.form.password
                        }
                        publicityLoginAction(param).then(response => {
                          console.log(response.data.info)
                          if(response.data.code == 0){
                                /* 权限
                                    if (this.form.userName === 'iview_admin') {
                                        Cookies.set('access', 0);
                                    } else {
                                        Cookies.set('access', 1);
                                    }
                                */
                                Cookies.set('token', response.data.token);
                                Cookies.set('user', this.form.userName);
                                Cookies.set('password', this.form.password);
                                this.$store.commit('setAvator', 'http://www.yingzy.com/Public/images/logo.png');
                                
                                //localStorage.token = response.data.info
                                localStorage.setItem('token', response.data.token)
                                //更新token
                                delayAction()
                                
                                this.$router.push({
                                    name: 'home_index'
                                });

                          }else{
                            this.$Message.info(response.data.msg);
                          }
                        }).catch( (error) => {
                          this.$Message.info("网络故障，请稍候再试");
                        });
                }

            });
        }
    }
};
</script>

<style>

</style>
