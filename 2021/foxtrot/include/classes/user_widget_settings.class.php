<?php
class user_widget_settings extends db{
  public $errors = '';
  protected $link;
  public $table = USER_WIDGET_SETTINGS;
  public $id;
  public $userId;
  public $dailyImporting;
  public $commissions;
  public $payroll;
  public $compliance;
  public $ytdProduction;
  public $dateUpdate;
  
  public function __construct($userId)
  {
    $link = parent::__construct();
    $this->link = $link;
    $userId = mysqli_real_escape_string($link, $userId);
    $query = "SELECT `uws`.*
        FROM `".$this->table."` AS `uws`
        WHERE `uws`.`user_id`=$userId";

    $res = $this->re_db_query($query);
    if($this->re_db_num_rows($res)>0)
    {
      $data = $this->re_db_fetch_array($res);
      $this->setPropertyValues($data);
    }
    else
    {
      $this->userId = $userId;
      $this->setDefaultValues();
    }
  }
  
  protected function setPropertyValues($data)
  {
    $this->id = $data['id'];
    $this->userId = $data['user_id'];
    $this->dailyImporting = $data['daily_importing'];
    $this->commissions = $data['commissions'];
    $this->payroll = $data['payroll'];
    $this->compliance = $data['compliance'];
    $this->ytdProduction = $data['ytd_production'];
    $this->dateUpdate = $data['date_updated'];
  }
  
  protected function setDefaultValues()
  {
    $this->dailyImporting = 'expanded';
    $this->commissions = 'expanded';
    $this->payroll = 'expanded';
    $this->compliance = 'expanded';
    $this->ytdProduction = 'expanded';
  }
  
  public function Save()
  {
    $values = array(
      'user_id' => $this->userId,
      'daily_importing' => $this->dailyImporting,
      'commissions' => $this->commissions,
      'payroll' => $this->payroll,
      'compliance' => $this->compliance,
      'ytd_production' => $this->ytdProduction,
      'date_updated' => $this->formatSqlDatetime(time()),
    );
    
    $result = false;
    if ($this->id > 0)
    {
      $this->re_db_perform($this->table, $values, 'update', 'id=' . $this->toSqlQuotedString($this->link, $this->id), 'db_link');
    }
    else
    {
      $result = $this->re_db_perform($this->table, $values, 'insert', '', 'db_link');
      if ($result)
        $this->id = $last_inserted_id = $this->re_db_insert_id();
    }
    
    return $result;
  }
}
?>