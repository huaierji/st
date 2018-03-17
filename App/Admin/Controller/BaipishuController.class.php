<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
class BaipishuController extends AdminController{
    //空操作
    public function _empty(){
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }
    /**
     * 显示白起书文章列表
     * 
     */
    public function index(){
    	$where['status'] =1; 
    	$list = M('Bai')->where($where)->select();
    	foreach($list as $k=>$v){
    		$list[$k]['title']=mb_substr((strip_tags(html_entity_decode($v['title']))),0,14,'utf-8');
    		$list[$k]['content']=mb_substr((strip_tags(html_entity_decode($v['content']))),0,30,'utf-8');
    	}
    	$this->assign('list',$list);
    	$this->display();
    }
    
    /**
     * 添加/修改系统公告，市场等文章
     * 
     */
    public function insert(){
        if(IS_POST){
        	$id = I('id');
            $data['title']=I('post.title');//标题
            $data['content'] =  I('post.content','','html_entity_decode');//内容
            $data['add_time'] = time();//添加时间
            $data['status'] = 1;
            
            $pic= $this->upload($_FILES["pic"]);
            //dump($pic);die;
            if($pic){
            	$data['pic']=$pic;
            }
            if(!empty($id)){
            	$data['id'] = $id;
            	$re = M('Bai')->save($data);
            }else{
            	$re = M('Bai')->add($data);//加入数据库
            }
            if($re===false){
                $this->error('操作失败');
                return;
            }else{
                $this->success('操作成功',I('post.url'));
                return;
           	}
           	
        }else{
        	//当获取到get，证明是修改功能， 显示所选的信息到页面
        	if($_GET['id']){
        		$id = I('get.id');
        		$list = M('Bai')->where("id = {$id}")->find();
        		$this->assign("list",$list);
        	} 	
            $this->display();
        }
    }


    /**
     * 删除文章
     * return boolen
     */
    public function delete(){
        $id=intval(I('get.id'));
        $re = M('Bai')->delete($id);
        if($re){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
            return;
        }
    }
    
    /**
     * 查找不同类型的文章分类
     * @param array $where 查询条件 数组形式
     * @return boolean|Array 返回查询数组 成功返回结果集 失败返回null
     */
    private function getArticleCategoryNameByArticleCategoryId($where){
    	$info= M('Article_category')->where($where)->select();
    	return $info;
    }
    /**
     * 查询对应条件的数据个数
     * @param array $where 查询条件
     * @param unknown $article_category_id 对应的文章类型id
     * @return boolean|Array 返回查询数量  成功返回数量 失败返回null
     */
    private function getCountArticleByWhere($where){
    	$count = M('Article')->join(C('DB_PREFIX')."article_category ON ".C('DB_PREFIX')."article.position_id = ".C('DB_PREFIX')."article_category.id")
                    ->where($where)
        			->count();
    	return $count;
    }
    /**
     * 查询对应文章信息
     * @param unknown $where 查询条件
     * @param unknown $article_category_id 对应的文章类型id
     * @return boolean|Array 返回查询数组 成功返回结果集 失败返回null
     */
    private function getContentArticleByWhere($where,$Page){
    	 $info = M('Article')->join(C('DB_PREFIX')."article_category ON ".C('DB_PREFIX')."article.position_id = ".C('DB_PREFIX')."article_category.id")
				    ->where($where)
				    ->order('add_time desc,sort')
				    ->limit($Page->firstRow.','.$Page->listRows)
				    ->select();
    	return $info;
    }
}