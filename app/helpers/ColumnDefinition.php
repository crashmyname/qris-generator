<?php
namespace Helpers;

class ColumnDefinition
{
    protected string $name;
    protected string $type;
    protected array $modifiers = [];
    protected ?string $foreignKey = null;
    protected ?string $foreignTable = null;
    protected ?string $onDelete = null;
    protected bool $nullable = false; // ← tambah ini

    public function __construct(string $type, string $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public function nullable()
    {
        $this->nullable = true;
        return $this;
    }

    public function notNullable()
    {
        $this->nullable = false;
        return $this;
    }

    public function default($value)
    {
        if (is_string($value) && strtoupper($value) === 'CURRENT_TIMESTAMP') {
            $this->modifiers[] = "DEFAULT CURRENT_TIMESTAMP";
        } else {
            $val = is_string($value) ? "'$value'" : $value;
            $this->modifiers[] = "DEFAULT $val";
        }
        return $this;
    }

    public function unique()
    {
        $this->modifiers[] = 'UNIQUE';
        return $this;
    }

    public function autoIncrement()
    {
        $this->modifiers[] = 'AUTO_INCREMENT';
        return $this;
    }

    public function primary()
    {
        $this->modifiers[] = 'PRIMARY KEY';
        return $this;
    }

    public function foreign()
    {
        $this->foreignKey = $this->name;
        return $this;
    }

    public function references(string $column)
    {
        $this->foreignKey = $column;
        return $this;
    }

    public function on(string $table)
    {
        $this->foreignTable = $table;
        return $this;
    }

    public function onDelete(string $action)
    {
        $this->onDelete = strtoupper($action);
        return $this;
    }

    public function build(): string
    {
        $sql = "`{$this->name}` {$this->type}";

        // Secara default NOT NULL kecuali diberi nullable()
        if ($this->nullable) {
            $sql .= ' NULL';
        } else {
            $sql .= ' NOT NULL';
        }

        if (!empty($this->modifiers)) {
            $sql .= ' ' . implode(' ', $this->modifiers);
        }

        if ($this->foreignKey && $this->foreignTable) {
            $fk = "FOREIGN KEY (`{$this->name}`) REFERENCES `{$this->foreignTable}`(`{$this->foreignKey}`)";
            if ($this->onDelete) {
                $fk .= " ON DELETE {$this->onDelete}";
            }
            return "$sql,\n    $fk";
        }

        return $sql;
    }
}
