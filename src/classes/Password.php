<?php

namespace App\Models;

use App\Models\Db;

class Password extends Db
{
    protected function fetchPasswordsFromUser($id): array
    {
        $sql = "SELECT * FROM passwords WHERE user_id=$id ";
        $passwords = $this->fetchAll($sql);

        if ($passwords) {
            return $passwords;
        }
        return [];
    }

    protected function createPassword(array $data): int
    {
        $passwordName = $data["password_name"];
        $passwordValue = $data["password_value"];
        $userId = $data["user_id"];
        $sql = "INSERT INTO passwords(password_name, password_value, user_id) VALUES(?, ?, ?)";
        $stmt = $this->prepareAndExecute($sql, "ssi", $passwordName, $passwordValue, $userId);

        if ($stmt) {
            $stmt->close();
            return 0;  //Senha criada com sucesso
        } else {
            return 1; //Falha ao criar senha
        }
    }

    protected function updatePassword(int $PasswordId, array $data): int
    {
        $updateString = "";

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $value = "'$value'";
            }

            $updateString  .= "$key = $value, ";
        }

        $updateString = rtrim($updateString, ', ');
        $sql = "UPDATE passwords SET $updateString WHERE password_id = ?";
        $stmt = $this->prepareAndExecute($sql, "i", $PasswordId);

        if ($stmt) {
            return 0; //Senha atualizada
        } else {
            return 1; //Erro ao atualizar senha
        }
    }

    protected function destroyPassword(int $PasswordId): int
    {
        $sql = "DELETE  FROM passwords WHERE password_id = ?";
        $stmt = $this->prepareAndExecute($sql, "i", $PasswordId);

        if ($stmt) {
            return 0; //Senha deletada
        } else {
            return 1; //Erro ao deletar senha
        }
    }
}
