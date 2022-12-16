<?php
class Modeldatareturn extends CI_Model
{
    // var $table = 'produk'; //nama tabel dari database
    var $column_order = array(null, 'returntgl', 'detjualfaktur', 'membernama', 'jualnapel', 'returnkodebarcode', 'returnjml', 'nmstt', null); //field yang ada di table user
    var $column_search = array('returntgl', 'detjualfaktur', 'membernama', 'jualnapel', 'nmstt', 'namaproduk'); //field yang diizin untuk pencarian 
    var $order = array('returntgl' => 'desc'); // default order 

    private function _get_datatables_query()
    {

        $this->db->select('penjualan_return.*,penjualan_detail.`detjualfaktur`,membernama,penjualan.`jualnapel`,returnjml,status_return.`nmstt`,namaproduk')
            ->from('penjualan_return')
            ->join('penjualan_detail', 'returndetjualid=detjualid')
            ->join('produk', 'kodebarcode = returnkodebarcode')
            ->join('penjualan', 'penjualan.`jualfaktur`=detjualfaktur')
            ->join('member', 'memberkode=penjualan.`jualmemberkode`', 'left')
            ->join('status_return', 'status_return.`id`= returnstatusid');

        // $this->db->from($this->table);

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

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->select('penjualan_return.*,penjualan_detail.`detjualfaktur`,membernama,penjualan.`jualnapel`,returnjml,status_return.`nmstt`,namaproduk')
            ->from('penjualan_return')
            ->join('penjualan_detail', 'returndetjualid=detjualid')
            ->join('produk', 'kodebarcode = returnkodebarcode')
            ->join('penjualan', 'penjualan.`jualfaktur`=detjualfaktur')
            ->join('member', 'memberkode=penjualan.`jualmemberkode`', 'left')
            ->join('status_return', 'status_return.`id`= returnstatusid');

        return $this->db->count_all_results();
    }
}