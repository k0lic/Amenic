<?php

    echo("
        <form action=\"AddMovieToDatabaseController/addMovie\" method=\"post\">
            <label for=\"movieID\">Movie id:</label>
            <input
                type=\"text\"
                id=\"movieID\"
                name=\"movieID\"
            />
            <button type=\"submit\">Pretrazi</button>
        </form>");