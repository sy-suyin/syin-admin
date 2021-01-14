namespace app\admin\repository;

use syin\Repository;

class {$class_name}Repository extends Repository {
	public function model(){
		return 'app\admin\model\{$class_name}';
	}
}