<script>
const deleteButton = (f, h) => {
    return h('Poptip', {
        props: {
            confirm: true,
            title: '您确定要删除这条数据吗?',
            transfer: true
        },
        on: {
            'on-ok': () => {
                f()
            }
        }
    }, [
        h('Button', {
            style: {
                margin: '0 5px'
            },
            props: {
                type: 'error',
                placement: 'top',
                size: 'small',
                icon:'trash-a'
            }
        }, '删除')
    ]);
}

const detailsButton = (f, h) => {
return h('Button', {
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
              f()
            }
          }
        }, '查看');
}
const editButton = (f, h) => {
return h('Button', {
          props: {
            type: 'primary',
            icon:"edit",
            size: 'small'
          },
          style: {
            marginRight: '5px'
          },
          on: {
            click: () => {
              f()
            }
          }
        }, '编辑')
}
    export default {
        deleteButton,
        detailsButton,
        editButton

    }
</script>
