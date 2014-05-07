<?php

/**
 * 逻辑区：评论管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name comment.logic.php
 * @version 1.0
 */

class CommentManageLogic
{
	
	public function show_summary($product_id)
	{
		$summary = $this->front_get_summary($product_id);
		$comments = $this->front_get_comments($product_id);
		if ($this->if_i_buyed_product($product_id))
		{
			$i_buyed = true;
			$comment_my = $this->front_get_comment_by_me($product_id);
		}
		else
		{
			$i_buyed = false;
			$comment_my = false;
		}
		include handler('template')->file('comment_summary');
	}
	
	public function front_get_summary($product_id)
	{
		$query = dbc(DBCMax)->select('comments')->in('count(1) as CCNT, avg(score) as CAVG')->where(array('product_id' => $product_id, 'status' => 'approved'))->limit(1)->done();
		return array(
			'count' => $query['CCNT'] ? $query['CCNT'] : 0,
			'average' => $query['CAVG'] ? round($query['CAVG'], 1) : 0
		);
	}
	
	public function front_get_comments($product_id, $user_id = null)
	{
		$user_id || $user_id = user()->get('id');
		$sql = dbc(DBCMax)->select('comments')->where('product_id='.$product_id.' and (status="approved" or user_id='.$user_id.')')->order('toped.desc')->order('timestamp_update.desc')->sql();
		$sql = page_moyo($sql);
		$comments = dbc(DBCMax)->query($sql)->done();
		return $comments;
	}
	
	private function front_get_comment_by_me($product_id, $user_id = null)
	{
		$user_id || $user_id = user()->get('id');
		return dbc(DBCMax)->select('comments')->where(array('product_id' => $product_id, 'user_id' => $user_id))->limit(1)->done();
	}
	
	private function if_i_buyed_product($product_id, $user_id = null)
	{
		$user_id || $user_id = user()->get('id');
		if ($user_id > 0)
		{
			return logic('product')->AlreadyBuyed($product_id, $user_id);
		}
		else
		{
			return false;
		}
	}
	
	public function source_get_one($id)
	{
		return dbc(DBCMax)->select('comments')->where(array('id' => $id))->limit(1)->done();
	}
	
	public function front_user_submit($product_id, $score, $content, $user_id = null, $comment_id = null)
	{
		if ((int)$score > 0 && $content)
		{
			$user_id || $user_id = user()->get('id');
			if ($this->if_i_buyed_product($product_id, $user_id))
			{
				if ($comment_id)
				{
					$comment = $this->source_get_one($comment_id);
					$comment || $comment['user_id'] = -1;
					if ($comment['user_id'] != $user_id)
					{
						return '您无法编辑其他人的评论！';
					}
					else
					{
						$up_id = $comment['id'];
						$data = array(
							'score' => $score,
							'content' => $score
						);
					}
				}
				else
				{
					$up_id = false;
					$data = array(
						'product_id' => $product_id,
						'user_id' => $user_id,
						'user_name' => user($user_id)->get('name'),
						'score' => $score,
						'content' => $content,
						'status' => ini('comment.dstatus'),
						'timestamp_update' => time()
					);
				}
				if ($up_id)
				{
					return dbc(DBCMax)->update('comments')->where(array('id' => $up_id))->data($data)->done();
				}
				else
				{
					return dbc(DBCMax)->insert('comments')->data($data)->done();
				}
			}
			else
			{
				return '您未购买过此产品，无法进行评价！';
			}
		}
		else
		{
			return '请选择评分并填写评价内容！';
		}
	}
	
	public function admin_form_submit($score, $content, $reply = null, $user_name = null, $product_id = null, $id = null)
	{
		$data = array(
			'score' => $score,
			'content' => $content
		);
		$reply && $data['reply'] = $reply;
		$data['user_name'] = $user_name ? $user_name : '买家';
		$product_id && $data['product_id'] = $product_id;
		if ($id)
		{
			$r = dbc(DBCMax)->update('comments')->where(array('id' => $id))->data($data)->done();
		}
		else
		{
			$r = dbc(DBCMax)->insert('comments')->data($data)->done();
		}
		return $r ? true : false;
	}
	
	public function admin_vlist()
	{
		$sql = dbc(DBCMax)->select('comments')->order('timestamp_update.desc')->sql();
		$sql = page_moyo($sql);
		$comments = dbc(DBCMax)->query($sql)->done();
		$comments || $comments = array();
		$products = array();
		foreach ($comments as $i => $comment)
		{
			if (isset($products[$comment['product_id']]))
			{
				$product = $products[$comment['product_id']];
			}
			else
			{
				$product = $products[$comment['product_id']] = logic('product')->SrcOne($comment['product_id']);
			}
			$comments[$i]['product'] = $product;
		}
		return $comments;
	}
	
	public function status_sync($id, $status)
	{
		$sa = array('auditing', 'approved', 'denied');
		if (in_array($status, $sa))
		{
			$r = dbc(DBCMax)->update('comments')->where(array('id' => $id))->data(array('status' => $status))->done();
			return $r ? true : false;
		}
		else
		{
			return false;
		}
	}
	
	public function toped_sync($id, $switch)
	{
		$sa = array('true', 'false');
		if (in_array($switch, $sa))
		{
			$toped = $switch == 'true' ? 1 : 0;
			$r = dbc(DBCMax)->update('comments')->where(array('id' => $id))->data(array('toped' => $toped))->done();
			return $r ? true : false;
		}
		else
		{
			return false;
		}
	}
	
	public function delete($id)
	{
		return dbc(DBCMax)->delete('comments')->where(array('id' => $id))->done();
	}
}

?>