<?php


use Helpers\SchemaBuilder;

class CreateQrisTable
{
    public function up(\PDO $pdo)
    {
        $table = new SchemaBuilder('qris');
        $table->id('qrisId');
        $table->bigInteger('userId');
        $table->string('merchantvendor',100);
        $table->string('merchantid',100);
        $table->string('merchantcriteria',100);
        $table->string('merchanttype',100);
        $table->string('merchantcategory',100);
        $table->string('merchantcurrency',100);
        $table->string('countryid',100);
        $table->string('merchantname',100);
        $table->string('merchantcity',100);
        $table->string('merchantpostalcode',100);
        $table->timestamp('created_at')->default('CURRENT_TIMESTAMP');
        $table->timestamp('updated_at')->default('CURRENT_TIMESTAMP');
        $sql = $table->buildCreateSQL();
        try {
             $pdo->exec($sql);
             echo "✅ Table 'qris' berhasil dibuat\n";
        } catch (\PDOException $e) {
             echo "❌ Gagal membuat tabel: ".$e->getMessage()."\n";
             echo "SQL:".$sql;
        }
    }

    public function down(PDO $pdo)
    {
        $table = new SchemaBuilder('qris');
        $pdo->exec($table->buildDropSQL());
    }
}
