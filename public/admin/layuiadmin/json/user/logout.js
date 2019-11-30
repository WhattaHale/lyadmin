//post()方式
$.ajax({
   url:'/index',
   type:'post',
   data:{name:'张三',age:12},
   success:function(data){
      	{
		  "code": 0
		  ,"msg": "退出成功"
		  ,"data": null
		}
   },
   error:function(error){
      console.log(error)
}