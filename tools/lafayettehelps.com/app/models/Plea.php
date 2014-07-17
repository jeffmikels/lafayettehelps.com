<?php
class Plea extends Eloquent
{
	protected $table = 'pleas';
	protected $properties = Array('id','user_id','summary','details','dollars','alternatives','deadline','status','verified_by');
	protected $softDelete = true;

	protected $validations = Array(
		'default' => '#^.*$#',
		'status' => 'refuse',
		'verified_by' => 'refuse'
	);

	public function __construct()
	{
		$this->user_id = me()->id;
	}

	public function restore()
	{
		$this->pledges()->restore();
		$this->comments()->restore();
		return parent::restore();
	}

	public function delete()
	{
		$this->pledges()->delete();
		$this->comments()->delete();
// 		if ($this->pledges)
// 		{
// 			foreach ($this->pledges as $pledge)
// 			{
// 				$pledge->delete();
// 			}
// 		}
		return parent::delete();
	}

	public function getProperties()
	{
		return $this->properties;
	}
	public function getPublicProperties()
	{
		return $this->public_properties;
	}
	
	public function permalink()
	{
		return route('pleadetail', array('id' => $this->id));
	}
	
	public function getOwnerId()
	{
		return $this->user_id;
	}
	
	public function author()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function comments()
	{
		return $this->morphMany('Comment', 'commentable');
	}

	public function pledges()
	{
		return $this->hasMany('Pledge');
	}
	
	public function monetaryPledges()
	{
		return $this->pledges()->where('dollars','>',0)->orderBy('created_at','ASC')->get();
	}

	public function alternativePledges()
	{
		return $this->pledges()->where('alternatives', '<>', '')->orderBy('created_at', 'ASC')->get();
	}

	public function totalPledged()
	{
		$total = 0;
		foreach ($this->monetaryPledges() as $pledge)
		{
			$total += $pledge->dollars;
		}
		return $total;
	}


	public function validateContent($prop, $content)
	{
		$property_exists = false;
		$content_matches = false;
		if (in_array($needle = $prop, $haystack = $this->getProperties())) $property_exists = true;
		/* for now, we assume the content matches */

		$pattern = $this->validations['default'];
		if (isset($this->validations[$prop])) $pattern = $this->validations[$prop];
		if ($pattern == 'refuse') return false;
		$content_matches = preg_match($pattern, $content);
		return ($property_exists && $content_matches);
	}


	public function validateAndUpdateFromArray($arr)
	{
		foreach ($arr as $prop=>$value)
		{
			if (! in_array($prop, $this->properties))
			{
				unset($arr[$prop]);
				continue;
			}
			if ($value && (! $this->validateContent($prop, $value)))
			{
				debug($prop . " did not validate");
				err($prop . " did not validate");
				return False;
			}
		}
		if ($this->updateFromArray($arr))
			return $this->id;
		else
			return False;

	}

	public function setDeadlineAttribute($deadline)
	{
		if ($deadline)
		{
			$this->attributes['deadline'] = date('Y-m-d', (strtotime($deadline)));
		}
		else
		{
			$this->attributes['deadline'] = '';
		}
	}

	public function getDeadlineAttribute()
	{
		if (! isset ($this->attributes['deadline'])) return '';

		$tmpdate = $this->attributes['deadline'];
		if ($tmpdate == '0000-00-00' || $tmpdate == '')
		{
			return '';
		}
		else
		{
			return date('m/d/Y', strtotime($tmpdate));
		}
	}


	public function updateFromArray($arr)
	{
		foreach ($arr as $prop=>$value)
		{
			if (in_array($prop, $this->getProperties()))
			{
				$this->$prop = $value;
			}
		}
		return($this->save());
	}

	public function expire()
	{
		$this->status = 'expired';
		$this->save();
	}
	
	public function expiring()
	{
		$this->status = 'expiring';
		$this->save();
	}
}
