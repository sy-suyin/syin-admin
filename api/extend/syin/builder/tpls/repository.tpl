namespace app\client\repository;

use syin\Repository;

class {$class_name}Repository extends Repository {
	public function model(){
		return 'app\client\model\{$class_name}';
	}
}