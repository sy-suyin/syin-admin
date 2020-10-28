import { list } from '@/config/table';

let config = <?php echo !empty($config) ? json_encode($config, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES  ) : '{}'; ?>;

config = {...list, ...config};
export default config;