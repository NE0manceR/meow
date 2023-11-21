<?php

class Product_Controller
{
  private $product_model;

  public function __construct(Product_Model $product_model)
  {
    $this->product_model = $product_model;
  }

  public function get_products($user_id)
  {
    $result = $this->product_model->get_products($user_id);
    return $result;
  }

  public function get_all_groups()
  {
    $result = $this->product_model->get_all_groups();

    return $result;
  }

  public function add_cat($name, $description, $img_src, $group_id)
  {
    $result = $this->product_model->add_cat($name, $description, $img_src, $group_id);

    return $result;
  }

  public function update_cat($name, $description, $img_src, $group_id)
  {
    $result = $this->product_model->update_cat($name, $description, $img_src, $group_id);

    return $result;
  }

  public  function delete_cat($id): array
  {
      return  $this->product_model->delete_cat($id);
  }
}
