<?php

class Todo
{
    // DB stuff
    /**
     * @var PDO
     */
    private $conn;
    private $table = 'todo';

    // Todo Properties
    public $id;
    public $title;
    public $status;
    public $from;
    public $to;
    public $PosFrom;
    public $PosTo;

    // Constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get Uncompleted tasks
    public function getUncompleted()
    {
        // Create query
        $query = 'SELECT * FROM ' . $this->table . ' WHERE  status=1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Completed tasks
    public function getCompleted()
    {
        // Create query
        $query = 'SELECT * FROM ' . $this->table . ' WHERE  status=2';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Create Task
    public function create()
    {
        // Create query
        if (!empty($this->title)) {
            $query = 'UPDATE ' . $this->table . ' WHERE status = 1';

            // Prepare statement
            $stmtUpdate = $this->conn->prepare($query);

            // Execute query
            if ($stmtUpdate->execute()) {
                // Create query
                $query = 'INSERT INTO ' . $this->table . ' SET title = :title, status = :status';

                // Prepare statement
                $stmt = $this->conn->prepare($query);

                // Clean data
                $this->title = filter_var($this->title, FILTER_SANITIZE_STRING);
                $this->description = filter_var($this->description, FILTER_SANITIZE_STRING);
                $this->status = filter_var(1, FILTER_SANITIZE_NUMBER_INT);

                // Bind data
                $stmt->bindParam(':title', $this->title);
                $stmt->bindParam(':description', $this->description);
                $stmt->bindParam(':status', $this->status);
                if ($stmt->execute()) {
                    return true;
                }
            }

        }


        return false;
    }

    // Update Post
    public function update()
    {
        // Create query
        $query = 'UPDATE  ' . $this->table . ' SET title=:title WHERE id=:id';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
        $this->title = filter_var($this->title, FILTER_SANITIZE_STRING);
        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }


        return false;
    }

    // Delete Post
    public function delete()
    {
        $query = 'SELECT title FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmtFind = $this->conn->prepare($query);

        // Clean data
        $this->id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
        // Bind data
        $stmtFind->bindParam(':id', $this->id);
        // Execute query
        if ($stmtFind->execute() and $stmtFind->rowCount() > 0) {
            // Create query
            $query = 'UPDATE  ' . $this->table . ' WHERE status = :status';

            // Prepare statement
            $stmtUpdate = $this->conn->prepare($query);

            // Clean data
            $this->status = filter_var($this->status, FILTER_SANITIZE_NUMBER_INT);

            // Bind data
            $stmtUpdate->bindParam(':status', $this->status);

            // Execute query
            if ($stmtUpdate->execute()) {
                $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

                // Prepare statement
                $stmt = $this->conn->prepare($query);

                // Clean data
                $this->id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
                // Bind data
                $stmt->bindParam(':id', $this->id);

                // Execute query
                if ($stmt->execute()) {
                    return true;

                }

            }

        }


        return false;
    }


    public function markAsCompleted()
    {
        $query = 'UPDATE ' . $this->table . ' WHERE status= :status';
        // Prepare statement
        $stmt = $this->conn->prepare($query);
        // Clean data
        $this->status = filter_var($this->status, FILTER_SANITIZE_NUMBER_INT);
        // Bind data
        $stmt->bindParam(':status', $this->status);
        if ($stmt->execute()) {
            // Create query
            $query = 'UPDATE ' . $this->table . ' status = 2';
            // Prepare statement
            $stmt = $this->conn->prepare($query);
            // Bind data
            if ($stmt->execute()) {
                $query = 'UPDATE  ' . $this->table . ' SET status=:status WHERE id=:id';
                // Prepare statement
                $stmt = $this->conn->prepare($query);
                // Clean data
                $this->id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
                $this->status = filter_var($this->status, FILTER_SANITIZE_NUMBER_INT);
                // Bind data
                $stmt->bindParam(':id', $this->id);
                $stmt->bindParam(':status', $this->status);
                if ($stmt->execute()) {
                    return true;
                }
            }
        }


        return false;
    }


}