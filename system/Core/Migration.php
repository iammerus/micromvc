<?php

    namespace MicroPos\Core;

    use Illuminate\Database\Capsule\Manager as Capsule;
    use Phinx\Migration\AbstractMigration;
    /**
     * Class Migration
     *
     * @package \MicroPos\Core
     */
    class Migration extends AbstractMigration
    {
        /** @var \Illuminate\Database\Capsule\Manager $capsule */
        public $capsule;
        /** @var \Illuminate\Database\Schema\Builder $capsule */
        public $schema;

        public function init()
        {
            $this->capsule = new Database();

            $this->capsule->initialize();

            $this->schema = $this->capsule->schema();
        }
    }
