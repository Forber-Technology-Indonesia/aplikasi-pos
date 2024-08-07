<?php
class Modelstokproduktglkadaluarsa extends CI_Model
{
    var $table = 'produk_tglkadaluarsa'; //nama tabel dari database
    var $column_order = array(null, 'tglkadaluarsa', null, null); //field yang ada di table user
    var $column_search = array('tglkadaluarsa'); //field yang diizin untuk pencarian 
    var $order = array('tglkadaluarsa' => 'asc'); // default order 


    private function _get_datatables_query($kode)
    {


        $this->db->from($this->table)->where('kodebarcode', $kode);

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($kode)
    {
        $this->_get_datatables_query($kode);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($kode)
    {
        $this->_get_datatables_query($kode);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($kode)
    {
        $this->db->from($this->table)->where('kodebarcode', $kode);
        return $this->db->count_all_results();
    }
}