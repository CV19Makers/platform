<?php

/**
 * Ushahidi Post Geometry Repository
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\App\Repository\Post;

use Ohanzee\DB;
use Ushahidi\Core\Entity\PostValue;
use Ushahidi\Core\Entity\PostValueRepository as PostValueRepositoryContract;

class GeometryRepository extends ValueRepository
{
    // OhanzeeRepository
    protected function getTable()
    {
        return 'post_geometry';
    }

    // Override selectQuery to fetch 'value' from db as text
    protected function selectQuery(array $where = [])
    {
        $query = parent::selectQuery($where);

        // Get geometry value as text
        $query->select(
            $this->getTable().'.*',
            // Fetch AsText(value) aliased to value
                [DB::expr('AsText(value)'), 'value']
        );

        return $query;
    }

    // Override createValue to save 'value' using GeomFromText
    public function createValue($value, $form_attribute_id, $post_id)
    {
        $value = DB::expr('GeomFromText(:text)')->param(':text', $value);

        return parent::createValue($value, $form_attribute_id, $post_id);
    }

    // Override updateValue to save 'value' using GeomFromText
    public function updateValue($id, $value)
    {
        $value = DB::expr('GeomFromText(:text)')->param(':text', $value);

        return parent::updateValue($id, $value);
    }
}
