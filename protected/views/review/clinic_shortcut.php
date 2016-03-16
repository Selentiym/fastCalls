<tr>
	<td><?php echo $clinic -> name; ?></td>
	<td><?php echo $clinic -> address; ?></td>
	<td><?php echo number_format($clinic -> sum/$clinic -> countReviews,2); ?></td>
	<td><?php echo $clinic -> countReviews; ?></td>
	<td><?php echo $clinic -> note; ?></td>
</tr>