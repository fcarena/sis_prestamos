<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reporte_todos_cobradores_fechas extends CI_Controller {
	public function __construct(){
		parent::__construct();
			$this->load->helper('url');
			$this->load->database();
			$this->load->library('grocery_crud');
			$this->load->model('zona_model');
			$this->load->model('prestamo_model');
			$this->load->model('cierre_x_cobrador_model');
			$this->load->library('dompdf_gen');
	}


	public function add_reporte_todos_cobradores_fechas(){
			if ($this->session->userdata('logueado')) {
			$data_usuario = array('id_usuario' =>$this->session->userdata('id'),
			'nombre_usuario'=>$this->session->userdata('nombre'),
			'id_nivel'=>$this->session->userdata('id_nivel'));
			if ($data_usuario['id_nivel']==1 || $data_usuario['id_nivel']==2) {
					$crud = new grocery_CRUD();
					$crud->set_theme('bootstrap');
					$crud->set_table('t_empresa');
					$crud->set_subject('Cliente');
					$output = $crud->render();
					
					$this->load->view('../../assets/inc/head_common_add', $output);
					$this->load->view('../../assets/inc/menu_lateral_admin',$data_usuario);
					$this->load->view('../../assets/inc/menu_superior',$data_usuario);
					$this->load->view('reporte/add_reporte_todos_cobradores_fechas');
					$this->load->view('../../assets/inc/footer_common',$output);
			}else{
					$crud = new grocery_CRUD();
					$crud->set_theme('bootstrap');
					$crud->set_table('t_empresa');
					$crud->set_subject('Cliente');
					$output = $crud->render();
					$this->load->view('../../assets/inc/head_common_add', $output);
					$this->load->view('error/permiso');
					$this->load->view('../../assets/inc/footer_common_add',$output);
			}
		}else{
						$this->session->set_flashdata('alerta', 'Debe Inciar Sesion');
						redirect('login/index','refresh');
		}
	}
	public function calculo_todos_cobradores(){
			if ($this->session->userdata('logueado')) {
			$data_usuario = array('id_usuario' =>$this->session->userdata('id'),
			'nombre_usuario'=>$this->session->userdata('nombre'),
			'id_nivel'=>$this->session->userdata('id_nivel'));
				$crud = new grocery_CRUD();
					$crud->set_theme('bootstrap');
					$crud->set_table('t_empresa');
					$crud->set_subject('Cliente');
					$output = $crud->render();
			if ($data_usuario['id_nivel']==1 || $data_usuario['id_nivel']==2) {
				$fecha_i=$this->input->post('txt_fecha_i',TRUE);
				$fecha_f=$this->input->post('txt_fecha_f',TRUE);
				if ($fecha_i==null||$fecha_f==null) {
								$this->session->set_flashdata('alerta', 'Debe Seleccionar los 2 registros');
								redirect('add_reporte_todos_cobradores_fechas','refresh');
				}
				$data = array('total' =>$this->cierre_x_cobrador_model->get_suma_cierre_todos($fecha_i, $fecha_f),
				'detallado'=>$this->cierre_x_cobrador_model->detallado_cierre_todos($fecha_i,$fecha_f),
				'fecha_i'=>$fecha_i,
				'fecha_f'=>$fecha_f);
					$this->load->view('../../assets/inc/head_common', $output);
					$this->load->view('../../assets/inc/menu_lateral_admin',$data_usuario);
					$this->load->view('../../assets/inc/menu_superior',$data_usuario);
					$this->load->view('reporte/ver_reporte_todos_fechas',$data );
					$this->load->view('../../assets/inc/footer_common',$output);
			}else{
					$crud = new grocery_CRUD();
					$crud->set_theme('bootstrap');
					$crud->set_table('t_empresa');
					$crud->set_subject('Cliente');
					$output = $crud->render();
					$this->load->view('../../assets/inc/head_common_add', $output);
					$this->load->view('error/permiso');
					$this->load->view('../../assets/inc/footer_common_add',$output);

			}
		}else{
						$this->session->set_flashdata('alerta', 'Debe Iniciar Sesion');
						redirect('login/index','refresh');
		}
	}
	public function imprimir_reporte(){
		if ($this->session->userdata('logueado')) {
			$data_usuario = array('id_usuario' =>$this->session->userdata('id'),
			'nombre_usuario'=>$this->session->userdata('nombre'),
			'id_nivel'=>$this->session->userdata('id_nivel'));
				
				$fecha_i=$this->input->post('txt_fecha_i',TRUE);
				$fecha_f=$this->input->post('txt_fecha_f',TRUE);
					$data = array('total' =>$this->cierre_x_cobrador_model->get_suma_cierre_todos($fecha_i, $fecha_f),
				'detallado'=>$this->cierre_x_cobrador_model->detallado_cierre_todos($fecha_i,$fecha_f),
				'fecha_i'=>$fecha_i,
				'fecha_f'=>$fecha_f);
					$crud = new grocery_CRUD();
					$crud->set_theme('bootstrap');
					$crud->set_table('t_gasto');
					$crud->set_subject('Gasto');
					$output = $crud->render();
					$this->load->view('../../assets/inc/head_common', $output);
					$this->load->view('reporte/imprimir_reporte_todos_fechas',$data );
					#toma lo que se muestra en pantalla
					$html = $this->output->get_output();
					# lo pasa a pdf
					$this->dompdf->load_html($html);
					$this->dompdf->render();
					$this->dompdf->stream("reporte_todos.pdf",array('Attachment'=>0));
		} else {
				$this->session->set_flashdata('alerta', 'Debe Iniciar Sesion');
				redirect('login/index','refresh');
		}
	}
/*fin del controller*/
}

