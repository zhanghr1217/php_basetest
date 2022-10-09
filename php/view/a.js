// 保存文章的函数
// isSending用于标识用户是否正在提交数据，初步防止用户连续点击重复提交的问题
var isSending = false
function saveArticle()
{
    // 如果为true，我们判定为用户已经正在提交数据，并且后端还没有返回响应，return false防止再次提交
    if (isSending === true) {
        return false
    }

    // 用户开始提交数据，我们改变按钮的文字来提示用户文章在发布过程中
    isSending = true
    $('.submit>button').text('发布中...')

    // 这个东西是jquery里面的，我们在前面的课中有讲过的哦
    $.post(
        '../controllers/addarticle.php',
        $('form').serialize(),
        function(ret){
            // notify是我们在页面上加的一个div用于提示信息的展示
            $('.notify').text(ret.msg)
            $('.submit>button').text('立即发布')
            setTimeout(function(){
                $('.notify').text('')
                window.location.href = 'detail.html?id=' + ret.data.article.id
                isSending = false
            }, 2000)
        },
        'json'
    )
}



// 获取文章详情
function getDetail(id) {
    $.get(
        '../controllers/articledetail.php?id=' + id,
        function(ret) {
            if (ret.status == 'fail') {
                $('.load').text(ret.msg)
            } else if (ret.status == 'success') {
                var detail = ret.data.detail
                console.log(detail)
            }
        },
        'json'
    )
}


// 获取文章详情
function articleList()
{
    // 这个东西是jquery里面的，我们在前面的课中有讲过的哦
    $.post(
        '../controllers/articlelist.php',
        $('form').serialize(),
        function(ret){
            // notify是我们在页面上加的一个div用于提示信息的展示
            $('.notify').text(ret.msg)
            $('.submit>button').text('立即查询')
            setTimeout(function(){
                $('.notify').text('')
                window.location.href = 'list.html'
                isSending = false
            }, 2000)
        },
        'json'
    )
}

